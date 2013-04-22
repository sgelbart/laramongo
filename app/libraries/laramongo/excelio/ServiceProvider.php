<?php namespace Laramongo\ExcelIo;

use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider {

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
        $this->app['excelio'] = $this->app->share(function($app)
        {
            return new ExcelIo;
        });
    }
}
