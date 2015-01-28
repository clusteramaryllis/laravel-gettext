<?php namespace Clusteramaryllis\Gettext;

use Illuminate\Support\ServiceProvider;

class GettextServiceProvider extends ServiceProvider
{
    /**
     * Default namespace
     */
    const DEFAULT_NAMESPACE = 'clusteramaryllis/gettext';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package(static::DEFAULT_NAMESPACE, static::DEFAULT_NAMESPACE, __DIR__);

        require_once __DIR__.'/helpers.php';

        $this->bootConfig();
        $this->bootCreateCommand();
        $this->bootUpdateCommand();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGettext();
        $this->registerRepository();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'gettext',
            'gettext.repository',
            'gettext.config',
            'gettext.create',
            'gettext.update',
        );
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function registerGettext()
    {
        $this->app['gettext'] = $this->app->share(function ($app) {
            $gettext = new Gettext();

            return $gettext->setTextDomain('messages', __DIR__."/locale");
        });
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app['gettext.repository'] = $this->app->share(function ($app) {
            return new Repository($app['files'], $app['path.base']);
        });
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function bootConfig()
    {
        $this->app['gettext.config'] = $this->app->share(function ($app) {
            $config = new Manager();

            return $config->setPackagePath($app['path.base']."/vendor");
        });
    }

    /**
     * Register create command.
     *
     * @return void
     */
    protected function bootCreateCommand()
    {
        $this->app['gettext.create'] = $this->app->share(function ($app) {
            return new Command\GettextCreateCommand($app['gettext.repository'], $app['gettext.config']);
        });

        $this->commands('gettext.create');
    }

    /**
     * Register update command.
     *
     * @return void
     */
    protected function bootUpdateCommand()
    {
        $this->app['gettext.update'] = $this->app->share(function ($app) {
            return new Command\GettextUpdateCommand($app['gettext.repository'], $app['gettext.config']);
        });

        $this->commands('gettext.update');
    }
}
