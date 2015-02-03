<?php namespace Clusteramaryllis\Gettext\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Clusteramaryllis\Gettext\Repositories\PoGenerator;
use Clusteramaryllis\Gettext\Config\Repositories\Manager;

class BaseCommand extends Command
{
    /**
     * Repository instance.
     *
     * @var \Clusteramaryllis\Gettext\Repositories\PoGenerator
     */
    protected $generator;

    /**
     * Manager instance.
     *
     * @var \Clusteramaryllis\Gettext\Config\Repositories\Manager
     */
    protected $config;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(Repository $generator, Manager $config)
    {
        parent::__construct();

        $this->generator = $generator;
        $this->config    = $config;
    }

    /**
     * Get the specified path to the files.
     *
     * @return array
     */
    protected function getPaths()
    {
        $path = $this->input->getOption('sources');

        if (! is_null($path)) {
            $paths = explode(",", preg_replace('/\s+/', '', $path));

            foreach ($paths as  $path) {
                $source[] = $this->laravel['path.base']."/{$path}";
            }

            return $source;
        }

        $bench = $this->input->getOption('bench');

        if (! is_null($bench)) {
            return array($this->laravel['path.base']."/workbench/{$bench}");
        }

        $package = $this->input->getArgument('package');

        if (! is_null($package)) {
            return array($this->config->getPackagePath()."/{$package}");
        }

        return $this->config->getSourcePaths();
    }

    /**
     * Get the locales.
     *
     * @return string
     */
    protected function getLanguages()
    {
        $locale     = $this->input->getOption('locale');
        $encoding   = $this->input->getOption('encoding');
        $pluralForms = $this->input->getOption('plural-forms');

        if (is_null($locale) && is_null($encoding) && is_null($pluralForms)) {
            return $this->config->getLanguages();
        }

        if (is_null($locale)) {
            $flag        = 'en';
            $localeValue = 'en_US';
        } else {
            $flag        = $locale;
            $localeValue = $locale;
        }

        if (is_null($encoding)) {
            $encodingValue = 'utf-8';
        } else {
            $encodingValue = $encoding;
        }

        if (is_null($pluralForms)) {
            $pluralFormsValue = null;
        } else {
            $pluralFormsValue = $pluralForms;
        }

        $languages[$flag] = array(
            'locale' => $localeValue,
            'encoding' => $encodingValue,
            'plural_forms' => $pluralFormsValue,
        );

        return $languages;
    }

    /**
     * Get the keywords.
     *
     * @return array
     */
    protected function getKeywords()
    {
        return $this->getArrayOption('keywords');
    }

    /**
     * Get the destination path.
     *
     * @return string
     */
    protected function getDestinationPath()
    {
        return $this->getPathOption('destination');
    }

    /**
     * Get the storage path.
     *
     * @return string
     */
    protected function getStoragePath()
    {
        return $this->getPathOption('storage');
    }

    /**
     * Get the po domain output.
     *
     * @return string
     */
    protected function getDomain()
    {
        return $this->getStringOption('domain');
    }

    /**
     * Get the project name.
     *
     * @return string
     */
    protected function getProject()
    {
        return $this->getStringOption('project');
    }

    /**
     * Get the translator name.
     *
     * @return string
     */
    protected function getTranslator()
    {
        return $this->getStringOption('translator');
    }

    /**
     * Get the poedit software version.
     *
     * @return string
     */
    protected function getVersion()
    {
        $option = $this->input->getOption('poedit-version');

        if (! is_null($option)) {
            return $option;
        }

        return $this->config->getVersion();
    }

    /**
     * Return the string from option.
     *
     * @return string
     */
    protected function getStringOption($name)
    {
        $option = $this->input->getOption($name);

        if (! is_null($option)) {
            return $option;
        }

        $method = "get".(ucfirst($name));

        return call_user_func_array(array($this->config, $method), array());
    }

    /**
     * Return the array from option separate by |.
     *
     * @return array
     */
    protected function getArrayOption($name)
    {
        $option = $this->input->getOption($name);

        if (! is_null($option)) {
            return explode(",", preg_replace('/\s+/', '', $option));
        }

        $method = "get".(ucfirst($name));

        return call_user_func_array(array($this->config, $method), array());
    }

    /**
     * Return the path from option.
     *
     * @return string
     */
    protected function getPathOption($name)
    {
        $option = $this->input->getOption($name);

        if (! is_null($option)) {
            return $this->laravel['path.base']."/{$option}";
        }

        $method = "get".(ucfirst($name))."Path";

        return call_user_func_array(array($this->config, $method), array());
    }

    /**
     * {@inheritDoc}
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::OPTIONAL, 'The name of the package to scan.'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return [
            ['bench', 'b', InputOption::VALUE_OPTIONAL, 'The name of the workbench to scan', null],
            ['sources', 's', InputOption::VALUE_OPTIONAL, 'The list of source directories to scan separate by , (relative to laravel base path)', null],
            ['destination', 'dt', InputOption::VALUE_OPTIONAL, 'The destination path of generated po files (relative to laravel base path)', null],
            ['storage', 'st', InputOption::VALUE_OPTIONAL, 'The storage path to store compiled blade files (relative to laravel base path)', null],
            ['locale', 'l', InputOption::VALUE_OPTIONAL, 'The locale language', null],
            ['keywords', 'k', InputOption::VALUE_OPTIONAL, 'The list of keywords separate by , ', null],
            ['domain', 'd', InputOption::VALUE_OPTIONAL, 'The output of po domain name', null],
            ['encoding', 'e', InputOption::VALUE_OPTIONAL, 'The encoding language', null],
            ['translator', 't', InputOption::VALUE_OPTIONAL, 'The translator name', null],
            ['project', 'p', InputOption::VALUE_OPTIONAL, 'The project name', null],
            ['plural-forms', 'pf', InputOption::VALUE_OPTIONAL, 'The plural forms', null],
        ];
    }
}
