<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Home
Route::get('/', 'HomeController@index');

// HealthCheck
Route::get(    'health', function(){ return "The application is healty"; } );

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

// Admin Root
Route::get('admin', function()
{
    return Redirect::action('Admin\CategoriesController@index'); 
});

// Admin\CategoriesController
Route::get(    'admin/categories',               'Admin\CategoriesController@index' );
Route::get(    'admin/category',                 'Admin\CategoriesController@index' );
Route::get(    'admin/category/create',          'Admin\CategoriesController@create');
Route::post(   'admin/category/store',           'Admin\CategoriesController@store');
Route::get(    'admin/category/{id}/edit',       'Admin\CategoriesController@edit');
Route::put(    'admin/category/{id}',            'Admin\CategoriesController@update');
Route::delete( 'admin/category/{id}',            'Admin\CategoriesController@destroy');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Route::get( 'login',           'UsersController@login');
Route::post('login',           'UsersController@do_login');
Route::get( 'logout',          'UsersController@logout');
