<?php namespace Admin;

use Category, View, Input, Redirect, URL;

class CategoriesController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories = Category::all();

		$this->layout->content = View::make('admin.categories.index')
			->with( 'categories', $categories );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout->content = View::make('admin.categories.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$category = new Category;

		$category->fill( Input::all() );

		// Save if valid
        if ( $category->save() )
        {
        	// Attach image to category
            if( Input::hasFile('image_file') )
            {
                $category->attachUploadedImage( Input::file('image_file') );
            }

            $success_message = "Nova categoria incluída com sucesso.".
                               " <b>Para ativar essa categoria adicione".
                               " alguns produtos. <a href='".
                               URL::action('Admin\ProductsController@index').
                               "'>Gerenciar Produtos</a></b>";

            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', $success_message );
        }
        else
        {
            // Get validation errors
            $error = $category->errors->all();

            return Redirect::action('Admin\CategoriesController@create')
                ->withInput()
                ->with( 'error', $error );
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
