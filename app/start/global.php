<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);

    if(App::environment() != 'local')
    {
        return Response::make(View::make('errors.runtime'), 500);
    }
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require __DIR__.'/../filters.php';

/*
|--------------------------------------------------------------------------
| Require The Helpers File
|--------------------------------------------------------------------------
|
*/

require __DIR__.'/../libraries/helpers.php';

/*
|--------------------------------------------------------------------------
| Require Exalead API
|--------------------------------------------------------------------------
|
*/

require __DIR__.'/../libraries/exalead/papi/PushAPI.inc';

/*
|--------------------------------------------------------------------------
| Additional View extensions
|--------------------------------------------------------------------------
|
*/

View::addExtension('blade.js','blade');
View::addExtension('js','blade');

/*
|--------------------------------------------------------------------------
| Default Application Template
|--------------------------------------------------------------------------
| When resolving template class return the template builder.
|
*/

App::bind('Template', 'Laramongo\TemplateBuilder\TemplateBuilder');
