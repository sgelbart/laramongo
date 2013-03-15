<?php namespace Admin;

use Input, View, Product, Redirect, Category;
use Zizaco\CsvToMongo\Importer;
use Zizaco\CsvToMongo\ImageUnzipper;

class ProductsController extends AdminController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = Input::get('page') ?: 1;

        $products = Product::all()
            ->sort(array('_id'=>'1'))
            ->limit(6)
            ->skip( ($page-1)*6 );

        $this->layout->content = View::make('admin.products.index')
            ->with( 'total_pages', round($products->count()/6) )
            ->with( 'page', $page )
            ->with( 'products', $products );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->layout->content = View::make('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $product = new Product;

        $product->fill( Input::all() );

        // Save if valid
        if ( $product->save() )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Novo produto incluído com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $product->errors->all();

            return Redirect::action('Admin\ProductsController@create')
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
        return Redirect::action('Admin\ProductsController@edit', ['id'=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        $product = Product::first($id);

        if(! $product)
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto não encontrado');
        }

        $this->layout->content = View::make('admin.products.edit')
            ->with( 'product', $product )
            ->with( 'action', 'Admin\ProductsController@update')
            ->with( 'method', 'PUT');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id)
    {
        $product = Product::first($id);

        if(! $product)
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto não encontrado');
        }

        $product->fill( Input::all() );

        // Save if valid
        if ( $product->save() )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Alterações salvas com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $product->errors->all();

            return Redirect::action('Admin\ProductsController@edit', ['id'=>$id])
                ->withInput()
                ->with( 'error', $error );
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = Product::first($id);

        $product->delete();

        return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto removido com sucesso' );
    }

    /**
     * Show the 'upload csv file' form
     *
     * @return Response
     */
    public function import()
    {
        $this->layout->content = View::make('admin.products.import');
    }

    /**
     * Process the received csv file
     *
     * @return Response
     */
    public function doImport()
    {
        $flash = '';

        if(Input::hasFile('zip_file'))
        {
            $zip_file = Input::file('zip_file');
            $path = app_path().'/storage/';
            $filename = 'zip_file'.time().'.zip';

            // Place file in storage
            $zip_file->move($path, $filename);

            // Extracts the images
            $imgu = new ImageUnzipper( $path.$filename );
            $imgu->extract();

            // Remove temporary file
            unlink($path.$filename);

            // Write flash message
            $flash = 'Imagens do arquivo zip foram importadas com sucesso!';
        }

        if(Input::hasFile('csv_file'))
        {
            $csv_file = Input::file('csv_file');
            $path = app_path().'/storage/';
            $filename = 'csv_file'.time().'.csv';

            // Place file in storage
            $csv_file->move($path, $filename);

            // Import file
            $importer = new Importer($path.$filename,'Product');
            $importer->import();

            // Remove temporary file
            unlink($path.$filename);

            $this->layout->content = View::make('admin.products.import_report')
                ->with( 'success', $importer->getSuccess() )
                ->with( 'failed', $importer->getErrors() )
                ->with( 'flash', $flash );

            return $this->layout;
        }
        else
        {
            return Redirect::action('Admin\ProductsController@import')
                ->with( 'error', 'Nenhum CSV foi carregado' )
                ->with( 'flash', $flash );
        }
        
        return '';
    }

}
