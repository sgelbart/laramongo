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

// Regions
Route::get("/regions/create", 'RegionsController@create');
Route::post("/regions/store", 'RegionsController@store');

// Home
Route::get('/', 'HomeController@index');

// ContentsController
Route::get(    'content/{slug}', 'ContentsController@show' );

// CategoriesController
Route::get(    'category/{id}', 'CategoriesController@show' );

// ProductsController
Route::get(    'product/{id}', 'ProductsController@show' );

// HealthCheck
Route::get(    'health', function(){
    if(extension_loaded ('newrelic'))
        newrelic_ignore_transaction();

    return "App is healthy";
} );

// HealthCheck
Route::get(    'health/details', function(){
    $hc = new Laramongo\HealthCheck\HealthCheck;
    return $hc->renderResults();
} );

Route::get('search', 'SearchEngineController@search');

// Ajax search at admin controllers
Route::get(    'search/products/{view}', 'SearchController@products');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Route::get( 'login',           'UsersController@login');
Route::post('login',           'UsersController@do_login');
Route::get( 'logout',          'UsersController@logout');

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
Route::get(    'admin/content/article/create',       'Admin\ContentsController@createArticle');
Route::get(    'admin/content/image/create',         'Admin\ContentsController@createImage');
Route::get(    'admin/content/video/create',         'Admin\ContentsController@createVideo');
Route::get(    'admin/content/shop/create',          'Admin\ContentsController@createShop');
Route::get(    'admin/content/{id}/edit',            'Admin\ContentsController@edit');
Route::post(   'admin/content/store',                'Admin\ContentsController@store');
Route::put(    'admin/content/{id}',                 'Admin\ContentsController@update');
Route::delete( 'admin/content/{id}',                 'Admin\ContentsController@destroy');
Route::get(    'admin/tags',                         'Admin\ContentsController@tags');
Route::delete( 'admin/content/{id}/rel/product/{product_id}',   'Admin\ContentsController@removeProduct');
Route::post(   'admin/content/{id}/rel/product/{product_id}',   'Admin\ContentsController@addProduct');
Route::delete( 'admin/content/{id}/rel/category/{category_id}', 'Admin\ContentsController@removeCategory');
Route::post(   'admin/content/{id}/rel/category/{category_id?}','Admin\ContentsController@addCategory');
Route::post(   'admin/content/{id}/tag',             'Admin\ContentsController@tagProduct');
Route::delete( 'admin/content/{id}/tag/{tag_id}',    'Admin\ContentsController@untagProduct');

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

// Admin\SynonymsController
Route::get('admin/synonyms', 'Admin\SynonymsController@index');
Route::get('admin/synonyms/create', 'Admin\SynonymsController@create');
Route::post('admin/synonyms/store', 'Admin\SynonymsController@store');
Route::get('admin/synonyms/{id}/edit', 'Admin\SynonymsController@edit');
Route::put('admin/synonyms/{id}', 'Admin\SynonymsController@update');
Route::delete('admin/synonyms/{id}', 'Admin\SynonymsController@destroy');
