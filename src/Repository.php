<?php namespace Clusteramaryllis\Gettext;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler as Compiler;
use Clusteramaryllis\Gettext\Exception\ResourceNotFoundException;

class Repository
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Default base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Repository instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  string                            $basePath
     * @return void
     */
    public function __construct(Filesystem $files, $basePath)
    {
        $this->files    = $files;
        $this->basePath = $basePath;
    }

    /**
     * Compile blade views.
     *
     * @param  array  $paths
     * @param  string $storagePath
     * @return $this
     */
    public function compileBladeViews(array $paths, $storagePath)
    {
        $this->makeDestination($storagePath);

        $compiler = new Compiler($this->files, $storagePath);

        foreach ($paths as $path) {
            $files = $this->files->glob(realpath($path).'/{,**/}*.php', GLOB_BRACE);

            foreach ($files as $file) {
                if (! Str::endsWith(strtolower($file), '.blade.php')) {
                    continue;
                }

                $compiler->setPath($file);

                $contents     = $compiler->compileString($this->files->get($file));
                $compiledPath = $compiler->getCompiledPath($compiler->getPath());

                $this->files->put($compiledPath.'.php', $contents);
            }
        }

        return $this;
    }

    /**
     * Build po file.
     *
     * @param  array  $paths
     * @param  string $compiledPath
     * @param  string $destinationPath
     * @param  string $locale
     * @param  string $encoding
     * @param  string $project
     * @param  string $translator
     * @param  array  $keywords
     * @param  string $pluralForms
     * @param  string $timestamp
     * @return string
     */
    public function preparePoContent(
        array $paths,
        $compiledPath,
        $destinationPath,
        $locale,
        $encoding,
        $project,
        $translator,
        $keywords,
        $pluralForms = null,
        $timestamp = null
    ) {
        $timestamp  = $timestamp ?: date("Y-m-d H:iO");
        $domainPath = $this->domainPath($destinationPath, $locale);

        // Split every 5 keywords & enter new line
        $chunk       = array_chunk($keywords, 5);
        $strKeywords = "\"X-Poedit-KeywordsList: ";

        foreach ($chunk as $key => $value) {
            if ($key !== 0) {
                $strKeywords .= "\"";
            }

            $strKeywords .= implode(';', $value);

            if ($key !== count($chunk) - 1) {
                $strKeywords .= ";\"\n";
            } else {
                $strKeywords .= "\\n\"\n";
            }
        }

        // Plural form
        $strPluralForms = is_string($pluralForms) ? 
            "\"Plural-Forms: {$pluralForms}\\n\"\n" :
            "";

        $this->makeDestination($domainPath);

        $relPath = $this->relativePath($domainPath, $this->basePath);

        $templates = array(
            "msgid \"\"\n",
            "msgstr \"\"\n",
            "\"Project-Id-Version: {$project}\\n\"\n",
            "\"POT-Creation-Date: {$timestamp}\\n\"\n",
            "\"PO-Revision-Date: {$timestamp}\\n\"\n",
            "\"Last-Translator: {$translator}\\n\"\n",
            "\"Language-Team: {$translator}\\n\"\n",
            "\"MIME-Version: 1.0\\n\"\n",
            "\"Content-Type: text/plain; charset={$encoding}\\n\"\n",
            "\"Content-Transfer-Encoding: 8bit\\n\"\n",
            "\"X-Poedit-Basepath: {$relPath}\\n\"\n",
            "\"X-Poedit-SourceCharset: {$encoding}\\n\"\n",
            $strKeywords,
            $strPluralForms,
            "\"Language: {$locale}\\n\"\n",
        );

        $paths = $this->stripPath($paths, $this->basePath);

        array_push($paths, $this->stripPath($compiledPath, $this->basePath));

        foreach ($paths as $key => $path) {
            array_push($templates, "\"X-Poedit-SearchPath-{$key}: {$path}\\n\"\n");
        }

        return implode("", $templates)."\n";
    }

    /**
     * Add new po file.
     *
     * @param array  $paths
     * @param string $storagePath
     * @param string $destinationPath
     * @param string $domain
     * @param string $locale
     * @param string $encoding
     * @param string $project
     * @param string $translator
     * @param array  $keywords
     * @param string $pluralForms
     * @param bool   $cached
     * @param string $timestamp
     */
    public function addLocale(
        array $paths,
        $storagePath,
        $destinationPath,
        $domain,
        $locale,
        $encoding,
        $project,
        $translator,
        $keywords,
        $pluralForms = null,
        $cached = false,
        $timestamp = null
    ) {
        $storagePath .= "/{$domain}";

        if (! $cached) {
            $this->compileBladeViews($paths, $storagePath);
        }

        $contents = $this->preparePoContent(
            $paths,
            $storagePath,
            $destinationPath,
            $locale,
            $encoding,
            $project,
            $translator,
            $keywords,
            $pluralForms,
            $timestamp
        );

        return $this->files->put(
            $this->domainPath($destinationPath, $locale)."/{$domain}.po", 
            $contents
        );
    }

    /**
     * Update existing po file.
     *
     * @param array  $paths
     * @param string $storagePath
     * @param string $destinationPath
     * @param string $domain
     * @param string $locale
     * @param string $encoding
     * @param string $project
     * @param string $translator
     * @param array  $keywords
     * @param string $pluralForms
     * @param bool   $cached
     * @param string $timestamp
     */
    public function updateLocale(
        array $paths,
        $storagePath,
        $destinationPath,
        $domain,
        $locale,
        $encoding,
        $project,
        $translator,
        $keywords,
        $pluralForms = null,
        $cached = false,
        $timestamp = null
    ) {
        $filename = $this->domainPath($destinationPath, $locale)."/{$domain}.po";

        if (! $this->files->exists($filename)) {
            return $this->addLocale(
                $paths,
                $storagePath,
                $destinationPath,
                $domain,
                $locale,
                $encoding,
                $project,
                $translator,
                $keywords,
                $pluralForms,
                $cached,
                $timestamp
            );
        }

        $storagePath .= "/{$domain}";

        if (! $cached) {
            $this->compileBladeViews($paths, $storagePath);
        }

        $oldContents = $this->files->get($filename);
        $newContents = $this->preparePoContent(
            $paths,
            $storagePath,
            $destinationPath,
            $locale,
            $encoding,
            $project,
            $translator,
            $keywords,
            $pluralForms,
            $timestamp
        );

        $contents = preg_replace('/^([^#])+:?/', $newContents, $oldContents);

        return $this->files->put($filename, $contents);
    }

    /**
     * Get domain path of specific locale.
     *
     * @param  string $path
     * @param  string $locale
     * @return string
     */
    public function domainPath($path, $locale)
    {
        return $path."/{$locale}/LC_MESSAGES";
    }

    /**
     * Strip path relative to other path.
     *
     * @param  string|array $path
     * @param  string       $relativeTo
     * @return string|array
     */
    public function stripPath($path, $relativeTo)
    {
        if (is_array($path)) {
            foreach ($path as $v) {
                $result[] = $this->stripPath($v, $relativeTo);
            }

            return $result;
        }

        if (! $this->files->exists($path)) {
            throw new ResourceNotFoundException("File\Directory does not exist at path {$path}");
        }

        if (! $this->files->exists($relativeTo)) {
            throw new ResourceNotFoundException("File\Directory does not exist at path {$relativeTo}");
        }

        $path       = str_replace('\\', '/', realpath($path));
        $relativeTo = str_replace('\\', '/', realpath($relativeTo));

        return str_replace(rtrim($relativeTo, "/\\")."/", "", $path);
    }

    /**
     * Convert to relative path between two paths.
     *
     * @param  string $path
     * @param  string $relativeTo
     * @return string
     */
    protected function relativePath($path, $relativeTo)
    {
        if (! $this->files->exists($path)) {
            throw new ResourceNotFoundException("File\Directory does not exist at path {$path}");
        }

        if (! $this->files->exists($relativeTo)) {
            throw new ResourceNotFoundException("File\Directory does not exist at path {$relativeTo}");
        }

        $path       = realpath($this->files->isDirectory($path) ? rtrim($path, "/\\") : dirname($path));
        $relativeTo = realpath($this->files->isDirectory($relativeTo) ? rtrim($relativeTo, "/\\") : dirname($relativeTo));
        $path       = explode('/', str_replace("\\", "/", $path));
        $relativeTo = explode('/', str_replace("\\", "/", $relativeTo));

        $relPath = $relativeTo;

        foreach ($path as $depth => $dir) {
            if (isset($relativeTo[$depth]) && $dir === $relativeTo[$depth]) {
                array_shift($relPath);
            } else {
                $remaining = count($path) - $depth;

                if ($remaining > 1) {
                    $padLength = (count($relPath) + $remaining) * -1;
                    $relPath   = array_pad($relPath, $padLength, "..");

                    break;
                }

                $relPath[0] = "./".$relPath[0];
            }
        }

        return implode("/", $relPath);
    }

    /**
     * Create the destination directory if it doesn't exist.
     *
     * @param  string $destination
     * @return void
     */
    protected function makeDestination($destination)
    {
        if (! $this->files->isDirectory($destination)) {
            $this->files->makeDirectory($destination, 0777, true);
        }
    }
}
