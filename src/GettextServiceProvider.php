<?php 

namespace Clusteramaryllis\Gettext;

use Illuminate\Support\ServiceProvider;

class GettextServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $defer = true;

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->setupConfig();

        $this->bootConfig();
        $this->bootCreateCommand();
        $this->bootUpdateCommand();

        $this->app->make('gettext.api')->setForcedRule(
            $this->app->make('config')->get('gettext.forced_rule')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->registerApi();
        $this->registerGettext();
        $this->registerGenerator();
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'gettext',
            'gettext.api',
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
     * Register gettext.
     *
     * @return void
     */
    protected function registerApi()
    {
        $this->app->singleton('gettext.api', function ($app) {
            return new Driver\GettextApi();
        });

        $this->app->alias('gettext.api', 'Clusteramaryllis\Gettext\Contracts\GettextApi');
    }

    /**
     * Register generator.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app->singleton('gettext.generator', function ($app) {
            return new Repositories\PoGenerator($app['files'], $app['path.base']);
        });
    }

    /**
     * Register gettext.
     *
     * @return void
     */
    protected function registerGettext()
    {
        $this->app->singleton('gettext', function ($app) {
            return new Gettext($app['gettext.api']);
        });
    }

    /**
     * Boot config.
     *
     * @return void
     */
    protected function bootConfig()
    {
        $this->app->singleton('gettext.config', function ($app) {
            return new Repositories\Manager();
        });
    }

    /**
     * Register create command.
     *
     * @return void
     */
    protected function bootCreateCommand()
    {
        $this->app->singleton('gettext.create', function ($app) {
            return new Command\GettextCreateCommand($app['gettext.generator'], $app['gettext.config']);
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
        $this->app->singleton('gettext.update', function ($app) {
            return new Command\GettextUpdateCommand($app['gettext.generator'], $app['gettext.config']);
        });

        $this->commands('gettext.update');
    }
}
