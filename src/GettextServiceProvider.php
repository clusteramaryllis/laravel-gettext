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

        $this->app->make('gettext.driver')->enableEmulator(
            $this->app->make('config')->get('gettext.enable_emulator')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->registerDriver();
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
            'gettext.driver',
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
     * Register gettext driver.
     *
     * @return void
     */
    protected function registerDriver()
    {
        $this->app->singleton('gettext.driver', function ($app) {
            return new Driver\GettextDriver();
        });

        $this->app->alias('gettext.driver', Contracts\GettextDriver::class);
    }

    /**
     * Register generator.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app->singleton('gettext.generator', function ($app) {
            return (new Repositories\PoGenerator($app['files'], $app['path.base']))
                ->setResolvedName('gettext');
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
            return new Gettext($app['gettext.driver']);
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
