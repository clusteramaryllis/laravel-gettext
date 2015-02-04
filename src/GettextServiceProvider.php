<?php namespace Clusteramaryllis\Gettext;

use Illuminate\Support\ServiceProvider;

class GettextServiceProvider extends ServiceProvider
{
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
        $this->setupConfig();

        require_once __DIR__."/helpers.php";

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
        $this->registerGenerator();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'gettext.generator',
            'gettext.config',
            'gettext.create',
            'gettext.update',
        ];
    }

    /**
     * Setup config.
     * 
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/gettext.php');

        $this->publishes([$source => config_path('gettext.php')]);

        $this->mergeConfigFrom($source, 'gettext');
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app['gettext.generator'] = $this->app->share(function ($app) {
            return new Repositories\PoGenerator($app['files'], $app['path.base']);
        });
    }

    /**
     * Boot config.
     *
     * @return void
     */
    protected function bootConfig()
    {
        $this->app['gettext.config'] = $this->app->share(function ($app) {
            $config = new Repositories\Manager();

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
