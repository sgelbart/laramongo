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

// ContentsController
Route::get(    'article/{slug}', 'ContentsController@show' );

// CategoriesController
Route::get(    'category/{id}', 'CategoriesController@show' );

// ProductsController
Route::get(    'product/{id}', 'ProductsController@show' );

// HealthCheck
Route::get(    'health', function(){
    return "App is healthy";
} );

// HealthCheck
Route::get(    'health/details', function(){
    $hc = new Laramongo\HealthCheck\HealthCheck;
    return $hc->renderResults();
} );

Route::get(    'search/products', 'SearchController@products');

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

// Admin\ContentsController
Route::get(    'admin/contents',                     'Admin\ContentsController@index' );
Route::get(    'admin/content',                      'Admin\ContentsController@index' );
Route::get(    'admin/create/article',               'Admin\ContentsController@createArticle');
Route::post(   'admin/content/store',                'Admin\ContentsController@store');
Route::get(    'admin/tags',                         'Admin\ContentsController@tags');

// Admin\CategoriesController
Route::get(    'admin/categories',                   'Admin\CategoriesController@index' );
Route::get(    'admin/category',                     'Admin\CategoriesController@index' );
Route::post(   'admin/category/tree',                'Admin\CategoriesController@tree');
Route::post(   'admin/categories/tree',              'Admin\CategoriesController@tree');
Route::get(    'admin/category/create',              'Admin\CategoriesController@create');
Route::post(   'admin/category/store',               'Admin\CategoriesController@store');
Route::get(    'admin/category/{id}/edit',           'Admin\CategoriesController@edit');
Route::put(    'admin/category/{id}',                'Admin\CategoriesController@update');
Route::get(    'admin/category/{id}',                'Admin\CategoriesController@show');
Route::delete( 'admin/category/{id}',                'Admin\CategoriesController@destroy');
Route::post(   'admin/category/{id}/attach',         'Admin\CategoriesController@attach');
Route::delete( 'admin/category/{id}/attach/{parent}','Admin\CategoriesController@detach');
Route::post(   'admin/category/{id}/characteristic', 'Admin\CategoriesController@add_characteristic');
Route::delete( 'admin/category/{id}/characteristic/{charac_name}', 'Admin\CategoriesController@destroy_characteristic');
Route::get(    'admin/category/{id}/validate',       'Admin\CategoriesController@validate_products');

// Admin\ProductsController
Route::get(    'admin/products',                      'Admin\ProductsController@index' );
Route::get(    'admin/product',                       'Admin\ProductsController@index' );
Route::get(    'admin/product/import',                'Admin\ProductsController@import' );
Route::get(    'admin/product/import_result/{id}',    'Admin\ProductsController@importResult' );
Route::post(   'admin/product/doImport',              'Admin\ProductsController@doImport' );
Route::get(    'admin/product/create',                'Admin\ProductsController@create' );
Route::post(   'admin/product',                       'Admin\ProductsController@store' );
Route::get(    'admin/product/{id}',                  'Admin\ProductsController@show' );
Route::get(    'admin/product/{id}/edit',             'Admin\ProductsController@edit' );
Route::put(    'admin/product/{id}',                  'Admin\ProductsController@update' );
Route::delete( 'admin/product/{id}',                  'Admin\ProductsController@destroy' );
Route::put(    'admin/product/{id}/characteristic',   'Admin\ProductsController@characteristic');
Route::get(    'admin/product/{category_id}/invalids','Admin\ProductsController@invalids');
Route::put(    'admin/product/{id}/fix',              'Admin\ProductsController@fix');
Route::put(    'admin/product/{id}/toggle',           'Admin\ProductsController@toggle');
Route::put(    'admin/product/{conj_id}/add/{id}',    'Admin\ProductsController@addToConjugated');
Route::put(    'admin/product/{conj_id}/remove/{id}', 'Admin\ProductsController@removeFromConjugated');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Route::get( 'login',           'UsersController@login');
Route::post('login',           'UsersController@do_login');
Route::get( 'logout',          'UsersController@logout');
