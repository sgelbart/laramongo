<?php namespace Laramongo\SearchEngine;

use Illuminate\Support\ServiceProvider;

class SearchEngineServiceProvider extends ServiceProvider {

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
        $this->app['ElasticSearchEngine'] = $this->app->share(function($app)
        {
            return new ElasticSearchEngine;
        });
    }
}
