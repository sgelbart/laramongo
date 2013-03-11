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

// CategoriesController
Route::get(    'category/{id}', 'CategoriesController@show' );

// ProductsController
Route::get(    'product/{id}', 'ProductsController@show' );

// HealthCheck
Route::get(    'health', function(){ return "It's working ;D"; } );

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

// Admin\ProductsController
Route::get(    'admin/products',                'Admin\ProductsController@index' );
Route::get(    'admin/product',                 'Admin\ProductsController@index' );
Route::get(    'admin/product/import',          'Admin\ProductsController@import' );
Route::post(   'admin/product/doImport',        'Admin\ProductsController@doImport' );
Route::get(    'admin/product/create',          'Admin\ProductsController@create' );
Route::post(   'admin/product',                 'Admin\ProductsController@store' );
Route::get(    'admin/product/{id}',            'Admin\ProductsController@show' );
Route::get(    'admin/product/{id}/edit',       'Admin\ProductsController@edit' );
Route::put(    'admin/product/{id}',            'Admin\ProductsController@update' );
Route::delete( 'admin/product/{id}',            'Admin\ProductsController@destroy' );

/*
|--------------------------------------------------------------------------
| Confide Routes
|--------------------------------------------------------------------------
|
*/

Route::get( 'user/login',           'UsersController@login');
Route::post('user/login',           'UsersController@do_login');
Route::get( 'user/logout',          'UsersController@logout');
