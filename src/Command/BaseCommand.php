<?php namespace Clusteramaryllis\Gettext\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Clusteramaryllis\Gettext\Repository;
use Clusteramaryllis\Gettext\Manager;

class BaseCommand extends Command
{
    /**
     * Repository instance.
     *
     * @var \Clusteramaryllis\Gettext\Repository
     */
    protected $repo;

    /**
     * Manager instance.
     *
     * @var \Clusteramaryllis\Gettext\Config\Manager
     */
    protected $config;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(Repository $repo, Manager $config)
    {
        parent::__construct();

        $this->repo   = $repo;
        $this->config = $config;
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
            $paths = explode(',', $path);

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
     * @return array
     */
    protected function getLocales()
    {
        return $this->getArrayOption('locales');
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
     * Get the encoding language.
     *
     * @return string
     */
    protected function getEncoding()
    {
        return $this->getStringOption('encoding');
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
            return explode(",", $option);
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
        return array(
            array('package', InputArgument::OPTIONAL, 'The name of the package to scan.'),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return array(
            array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to scan.', null),
            array('sources', null, InputOption::VALUE_OPTIONAL, 'The list of source directories to scan separate by , (relative to laravel base path). ', null),
            array('destination', null, InputOption::VALUE_OPTIONAL, 'The destination path of generated po files (relative to laravel base path).', null),
            array('storage', null, InputOption::VALUE_OPTIONAL, 'The storage path to store compiled blade files (relative to laravel base path).', null),
            array('locales', null, InputOption::VALUE_OPTIONAL, 'The list of locales separate by ,.', null),
            array('keywords', null, InputOption::VALUE_OPTIONAL, 'The list of keywords separate by ,.', null),
            array('domain', null, InputOption::VALUE_OPTIONAL, 'The output of po domain name.', null),
            array('encoding', null, InputOption::VALUE_OPTIONAL, 'The encoding language.', null),
            array('translator', null, InputOption::VALUE_OPTIONAL, 'The translator name.', null),
            array('project', null, InputOption::VALUE_OPTIONAL, 'The project name.', null),
            array('poedit-version', null, InputOption::VALUE_OPTIONAL, 'The poedit software version.', null),
        );
    }
}
