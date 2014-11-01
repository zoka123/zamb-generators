<?php namespace Zoka\ZambGenerators;

use Illuminate\Support\ServiceProvider;
use Zoka\ZambGenerators\Commands\ZambGeneratorCommand;

class ZambGeneratorsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Booting
     */
    public function boot()
    {
        $this->package('zoka/zamb-generators');
    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function register()
    {
        $this->app['zamb.generator.generate'] = $this->app->share(function ($app) {
            $generator = $this->app->make('Zoka\ZambGenerators\Generator');

            return new ZambGeneratorCommand($generator);
        });

        $this->commands('zamb.generator.generate');
    }

    /**
     * Register the model generator
     */
    protected function registerModel()
    {
        $this->app['generate.model'] = $this->app->share(function ($app) {
            $generator = $this->app->make('Way\Generators\Generator');

            return new ModelGeneratorCommand($generator);
        });

        $this->commands('generate.model');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
