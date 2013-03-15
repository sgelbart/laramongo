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
Route::get(    'admin/category/tree',                'Admin\CategoriesController@tree');
Route::get(    'admin/categories/tree',              'Admin\CategoriesController@tree');
Route::get(    'admin/categories',                   'Admin\CategoriesController@index' );
Route::get(    'admin/category',                     'Admin\CategoriesController@index' );
Route::get(    'admin/category/create',              'Admin\CategoriesController@create');
Route::post(   'admin/category/store',               'Admin\CategoriesController@store');
Route::get(    'admin/category/{id}/edit',           'Admin\CategoriesController@edit');
Route::put(    'admin/category/{id}',                'Admin\CategoriesController@update');
Route::get(    'admin/category/{id}',                'Admin\CategoriesController@show');
Route::delete( 'admin/category/{id}',                'Admin\CategoriesController@destroy');
Route::post(   'admin/category/{id}/attach',         'Admin\CategoriesController@attach');
Route::delete( 'admin/category/{id}/attach/{parent}','Admin\CategoriesController@detach');

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
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Route::get( 'login',           'UsersController@login');
Route::post('login',           'UsersController@do_login');
Route::get( 'logout',          'UsersController@logout');
