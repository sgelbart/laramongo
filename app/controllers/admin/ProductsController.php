<?php namespace Admin;

use Input, View, Product, ConjugatedProduct, Redirect, Response, Category;
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
        $search = Input::get('search');
        $deactivated = Input::get('deactivated') != 'true';

        $page = Input::get('page') ?: 1;

        if($search)
        {
            $query = [ '$or'=> [
                ['name'=> new \MongoRegex('/^'.$search.'/i')],
                ['lm'=> new \MongoRegex('/^'.$search.'/i')]
            ]];
        }
        else
        {
            $query = array();
        }

        if($deactivated)
        {
            $query = array_merge($query,['deactivated'=>null]);
        }

        $products = Product::where($query)
            ->sort(['_id'=>1])
            ->limit(6)
            ->skip( ($page-1)*6 );

        if( \Input::get('ajax') || \Request::ajax() )
        {
            return View::make('admin.products.quicksearch')
                ->with( 'total_pages', round($products->count()/6) )
                ->with( 'page', $page )
                ->with( 'products', $products );
        }
        else
        {
            $this->layout->content = View::make('admin.products.index')
                ->with( 'total_pages', round($products->count()/6) )
                ->with( 'page', $page )
                ->with( 'products', $products );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $leafs = Category::toOptions( ['kind'=>'leaf'] );

        $this->layout->content = View::make('admin.products.create')
            ->with( 'leafs', $leafs );
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
        if ( $product->isValid() )
        {
            $product->save();

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

        if($product->conjugated)
        {
            $conjProduct = new ConjugatedProduct;
            $conjProduct->parseDocument( $product->toArray() );
            $product = $conjProduct;
        }

        $category = $product->category();
        $leafs = Category::toOptions( ['kind'=>'leaf'] );

        $this->layout->content = View::make('admin.products.edit')
            ->with( 'product', $product )
            ->with( 'leafs', $leafs )
            ->with( 'category', $category )
            ->with( 'action', 'Admin\ProductsController@update' )
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
        if ( $product->isValid() )
        {
            $product->save();

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
     * Update the characteristics (details) of a product
     * 
     * @return Response
     */
    public function characteristic($id)
    {
        $product = Product::first($id);
        $category = $product->category();

        if(! $product)
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto não encontrado');
        }

        $details = array();
        foreach ($category->characteristics() as $charac) {
            $details[clean_case($charac->name)] = Input::get(clean_case($charac->name));

            if(! $details[clean_case($charac->name)])
                $details[clean_case($charac->name)] = Input::get(str_replace(' ', '_', clean_case($charac->name)));
        }
        
        $product->details = $details;

        // Save if valid
        if ( $product->save() )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'As caracteristicas do produto foram salvas com sucesso' );
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

        if ( $product->delete() )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto removido com sucesso' );
        }
        else
        {
            if( $product->errors )
            {
                $errorMessage = $product->errors->first(0);
            }
            else
            {
                $errorMessage = 'Não foi possível excluir o produto. Tente novamente mais tarde.';
            }

            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', $errorMessage);
        }

        
    }

    /**
     * Show the 'upload csv file' form
     *
     * @return Response
     */
    public function import()
    {
        $leafs = Category::toOptions( ['kind'=>'leaf'] );

        $this->layout->content = View::make('admin.products.import')
            ->with( 'leafs', $leafs )
            ->with( 'conjugated', Input::get('conjugated') );
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

            // Creates the import object
            $import = new Import;
            $import->filename = $path.$filename;
            $import->category = Input::get('category');
            $import->isConjugated = Input::get('conjugated');
            $import->save();

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

    /**
     * Index all the invalid products within a category
     * 
     * @return Response
     */
    public function invalids($category_id)
    {
        $products = Product::where(['category'=>$category_id, 'state'=>'invalid']);
        $category = Category::first($category_id);

        $this->layout->content = View::make('admin.products.invalids')
            ->with( 'products', $products )
            ->with( 'category', $category );
    }

    /**
     * Updates some of the characteristics of a product. Should be called
     * by ajax. So it answers javascript.
     *
     * @return Response (Javascript)
     */
    public function fix($id)
    {
        $product = Product::first($id);

        if(! $product)
        {
            return Response::make('Product not found', 404);
        }

        $input = Input::all();
        $details = array();

        foreach ($input as $key => $value) {
            // Replaces underline with spaces
            $details[str_replace('_', ' ', $key)] = $value; 
        }

        $product->details = array_merge(
            $product->details,
            $details
        );

        // Save if valid
        if ( $product->save(true) )
        {
            return View::make('admin.products.fix')
                ->with('product', $product);
        }
    }

    /**
     * Toggle the deactivation of a product. Should be called
     * by ajax. So it answers javascript.
     *
     * @return Response (Javascript)
     */
    public function toggle($id)
    {
        $product = Product::first($id);

        if(! $product)
        {
            return Response::make('Product not found', 404);
        }

        if($product->deactivated)
        {
            $product->activate();
        }
        else
        {
            $product->deactivate();
        }
        $product->save();

        return View::make('admin.products.toggle')
                ->with('product', $product);
    }

    /**
     * Add a product to a conjugated one
     */
    public function addToConjugated($conj_id, $id)
    {
        $conjProduct = ConjugatedProduct::first($conj_id);
        $conjProduct->attachToConjugated( (int)$id );

        if($conjProduct->save())
        {
            return Redirect::action('Admin\ProductsController@edit', ['id'=>$conj_id, 'tab'=>'product-conjugation'])
                ->with( 'flash', 'Produto adicionado com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $conjProduct->errors->all();

            return Redirect::action('Admin\ProductsController@edit', ['id'=>$conj_id, 'tab'=>'product-conjugation'])
                ->withInput()
                ->with( 'error', $error );
        }
    }

    /**
     * Remove product from conjugated
     */
    public function removeFromConjugated($conj_id, $id)
    {
        $conjProduct = ConjugatedProduct::first($conj_id);
        $conjProduct->detach( 'conjugated', (int)$id );

        if($conjProduct->save())
        {
            return Redirect::action('Admin\ProductsController@edit', ['id'=>$conj_id, 'tab'=>'product-conjugation'])
                ->with( 'flash', 'Produto removido com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $conjProduct->errors->all();

            return Redirect::action('Admin\ProductsController@edit', ['id'=>$conj_id, 'tab'=>'product-conjugation'])
                ->withInput()
                ->with( 'error', $error );
        }
    }
}
