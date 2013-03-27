<?php namespace Laramongo\Nas;

use Illuminate\Support\ServiceProvider;

class S3ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['s3.commands'] = $this->app->share(function($app)
        {
            return new Commands\S3($app);
        });

        $this->commands(
            's3.commands'
        );
    }
}
