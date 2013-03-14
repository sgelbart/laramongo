<?php namespace Admin;

use Category, View, Input, Redirect, URL, MongoId;

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
        $category = Category::first($id);

        if(! $category)
        {
            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', 'Categoria não encontrada' );
        }

        $this->layout->content = View::make('admin.categories.hierarchy')
            ->with( 'category', $category );
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function tree()
    {
        $this->layout->content = View::make('admin.categories.tree');
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$category =   Category::first($id);
        $categories = Category::allToOptions();

        if(! $category)
        {
            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', 'Categoria não encontrada' );
        }

        $this->layout->content = View::make('admin.categories.edit')
            ->with( 'category', $category )
            ->with( 'categories', $categories )
            ->with( 'action', 'Admin\CategoriesController@update')
            ->with( 'method', 'PUT');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$category = Category::first($id);

        if(! $category)
        {
            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', 'Categoria não encontrada');
        }

        $category->fill( Input::all() );

        // Save if valid
        if ( $category->save() )
        {
            // Attach image to category
            if( Input::hasFile('image_file') )
            {
                $category->attachUploadedImage( Input::file('image_file') );
            }
            
            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', 'Alterações salvas com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $category->errors->all();

            return Redirect::action('Admin\CategoriesController@edit', ['id'=>$id])
                ->withInput()
                ->with( 'error', $error );
        }
	}

    /**
     * Attach a parent to the specified resource.
     *
     * @return Response
     */
    public function attach($id)
    {
        $category = Category::first($id);
        $parent = Category::first( Input::get('parent') );

        if(! ($parent && $category) )
        {
            return Redirect::action('Admin\CategoriesController@index')
                ->with( 'flash', 'Categoria não encontrada');
        }

        // Attach parent and save
        $category->attachToParents($parent);
        $category->save();
        
        return Redirect::action('Admin\CategoriesController@edit', ['id'=>$id])
            ->with( 'flash', 'Alterações salvas com sucesso' );
    }

    /**
     * Detach a parent to the specified resource.
     *
     * @return Response
     */
    public function detach($id, $parent_id)
    {
        $category = Category::first($id);

        // Detach parent and save
        $category->detach('parents', $parent_id);
        $category->save();
        
        return Redirect::action('Admin\CategoriesController@edit', ['id'=>$id])
            ->with( 'flash', 'Alterações salvas com sucesso' );
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
