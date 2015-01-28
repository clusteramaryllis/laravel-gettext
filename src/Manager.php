<?php namespace Clusteramaryllis\Gettext;

use Illuminate\Support\Facades\Config;

class Manager
{
    /**
     * Default package config.
     */
    const PACKAGE_DEFAULT_CONFIG = 'clusteramaryllis/gettext::config';

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Default package path.
     *
     * @var string
     */
    protected $packagePath = __DIR__;

    /**
     * Constructor.
     *
     * @param array  $config
     * @param string $namespace
     */
    public function __construct(array $config = array())
    {
        $this->setConfigs($config ?: Config::get(static::PACKAGE_DEFAULT_CONFIG));
    }

    /**
     * Set configs.
     *
     * @param  array $config
     * @return $this
     */
    public function setConfigs(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config.
     *
     * @param  string $name
     * @param  string $default
     * @return mixed
     */
    protected function getConfig($name, $default)
    {
        return $name ? $this->config[$name] : $default;
    }

    /**
     * Set config.
     *
     * @param  string $name
     * @param  string $value
     * @return $this
     */
    protected function setConfig($name, $value)
    {
        $this->config[$name] = $value;

        return $this;
    }

    /**
     * Set the default package path.
     *
     * @param  string $packagePath
     * @return $this
     */
    public function setPackagePath($packagePath)
    {
        $this->packagePath = $packagePath;

        return $this;
    }

    /**
     * Set the default source paths.
     *
     * @param  array $paths
     * @return $this
     */
    public function setSourcePaths(array $paths)
    {
        return $this->setConfig("source_paths", $paths);
    }

    /**
     * Set the default storage path.
     *
     * @param  string $storagePath
     * @return $this
     */
    public function setStoragePath($storagePath)
    {
        return $this->setConfig("storage_path", $storagePath);
    }

    /**
     * Set the default destination path.
     *
     * @param  array $destinationPath
     * @return $this
     */
    public function setDestinationPath($destinationPath)
    {
        return $this->setConfig("destination_path", $destinationPath);
    }

    /**
     * Set the default domain filename.
     *
     * @param  string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        return $this->setConfig("domain", $domain);
    }

    /**
     * Set the default locales.
     *
     * @param  array $locales
     * @return $this
     */
    public function setLocales(array $locales)
    {
        return $this->setConfig("locales", $locales);
    }

    /**
     * Set the default keywords.
     *
     * @param  array $keywords
     * @return $this
     */
    public function setKeywords(array $keywords)
    {
        return $this->setConfig("keywords", $keywords);
    }

    /**
     * Set the default encoding.
     *
     * @param  string $encoding
     * @return $this
     */
    public function setEncoding($encoding)
    {
        return $this->setConfig("encoding", $encoding);
    }

    /**
     * Set the default translator name.
     *
     * @param  string $translator
     * @return $this
     */
    public function setTranslator($translator)
    {
        return $this->setConfig("translator", $translator);
    }

    /**
     * Set the default project name.
     *
     * @param  string $project
     * @return $this
     */
    public function setProject($project)
    {
        return $this->setConfig("project_name", $project);
    }

    /**
     * Set the default poedit verison.
     *
     * @param  string $version
     * @return $this
     */
    public function setVersion($version)
    {
        return $this->setConfig("poedit_version", $version);
    }

    /**
     * Get the default package path.
     *
     * @return string
     */
    public function getPackagePath()
    {
        return $this->packagePath;
    }

    /**
     * Get the default paths.
     *
     * @param  array $default
     * @return array
     */
    public function getSourcePaths($default = array())
    {
        return $this->getConfig("source_paths", $default);
    }

    /**
     * Get the default storage path.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getStoragePath($default = null)
    {
        return $this->getConfig("storage_path", $default);
    }

    /**
     * Get the target destination path for the files.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getDestinationPath($default = null)
    {
        return $this->getConfig("destination_path", $default);
    }

    /**
     * Get the default domain filename.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getDomain($default = null)
    {
        return $this->getConfig("domain", $default);
    }

    /**
     * Get the default locale.
     *
     * @param  array $default
     * @return array
     */
    public function getLocales($default = array())
    {
        return $this->getConfig("locales", $default);
    }

    /**
     * Get the default keywords.
     *
     * @param  array $default
     * @return array
     */
    public function getKeywords($default = array())
    {
        return $this->getConfig("keywords", $default);
    }

    /**
     * Get the default translator name.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getTranslator($default = null)
    {
        return $this->getConfig("translator", $default);
    }

    /**
     * Get the default project name.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getProject($default = null)
    {
        return $this->getConfig("project_name", $default);
    }

    /**
     * Get the default encoding.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getEncoding($default = null)
    {
        return $this->getConfig("encoding", $default);
    }

    /**
     * Get the default poedit version.
     *
     * @param  mixed  $default
     * @return string
     */
    public function getVersion($default = null)
    {
        return $this->getConfig("poedit_version", $default);
    }
}
