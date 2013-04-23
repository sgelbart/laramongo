<?php namespace Laramongo\ImageGrabber;

use Illuminate\Support\ServiceProvider;

class ImageGrabberServiceProvider extends ServiceProvider {

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
        $this->app['imagegrabber'] = $this->app->share(function($app)
        {
            return new ImageGrabber;
        });

        $this->app['ImageImporter'] = $this->app->share(function()
        {
            return new ImageImporter;
        });
    }
}
