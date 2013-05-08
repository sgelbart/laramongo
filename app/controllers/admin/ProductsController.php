<?php namespace Admin;

use Input, View, Product, ConjugatedProduct;
use Redirect, Response, Category, Import, MassImport;
use Zizaco\CsvToMongo\Importer;
use Zizaco\CsvToMongo\ImageUnzipper;

class ProductsController extends AdminController {

    protected $productRepo;

    function __construct( \ProductRepository $productRepo )
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = Input::get('page');

        $products = $this->productRepo->search( Input::get('search'), Input::get('deactivated') );
        $total_pages = $this->productRepo->pageCount( $products );
        $products = $this->productRepo->paginate( $products, $page );

        $viewData = [
            'products' => $products,
            'page' => $page,
            'total_pages' => $total_pages,
        ];

        if( \Input::get('ajax') || \Request::ajax() )
        {
            return View::make('admin.products.quicksearch', $viewData);
        }
        else
        {
            $this->layout->content = View::make('admin.products.index', $viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $leafs = Category::toOptions( ['type'=>'leaf'] );

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
        if ( $this->productRepo->createNew( $product ) )
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
        $product = $this->productRepo->first($id);

        if(! $product)
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Produto não encontrado');
        }

        $category = $product->category();
        $leafs = Category::toOptions( ['type'=>'leaf'] );

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
        $product = $this->productRepo->first($id);

        if($product)
            $product->fill( Input::all() );

        if ( $this->productRepo->update( $product ) )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'Alterações salvas com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = ($product) ? $product->errors->all() : array();

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
        $product = $this->productRepo->first($id);

        if($product && $this->productRepo->updateCharacteristics( $product, Input::all() ) )
        {
            return Redirect::action('Admin\ProductsController@index')
                ->with( 'flash', 'As caracteristicas do produto foram salvas com sucesso' );
        }
        else
        {
            // Get validation errors
            $error = $product->errors->all();

            return Redirect::action('Admin\ProductsController@edit', ['id'=>$id, 'tab'=>'product-characteristcs'])
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
        $leafs = Category::toOptions( ['type'=>'leaf'] );

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
            $path = '/../public/uploads/';

            if($csv_file->getClientMimeType() == 'application/zip')
            {
                $filename = 'mass_import'.time().'.zip';

                // Place file in storage
                $csv_file->move(app_path().$path, $filename);

                // Creates the import object
                $import = new MassImport;
                $import->filename = $path.$filename;
                $import->save();
            }
            else
            {
                $filename = 'excel_file'.time().'.xlsx';

                // Place file in storage
                $csv_file->move(app_path().$path, $filename);

                // Creates the import object
                $import = new Import;
                $import->filename = $path.$filename;
                $import->save();
            }

            return Redirect::action('Admin\ProductsController@importResult', ['id'=>$import->_id])
                ->with( 'flash', $flash );
        }
        else
        {
            return Redirect::action('Admin\ProductsController@import')
                ->with( 'error', 'Nenhum CSV foi carregado' )
                ->with( 'flash', $flash );
        }
        
        return '';
    }

    public function importResult($id)
    {
        $import = Import::first($id);

        if($import && $import->isDone())
        {
            $this->layout->content = View::make('admin.products.import_report')
                ->with( 'success', $import->success )
                ->with( 'failed', $import->fail )
                ->with( 'category_id', '$import->category' );
        }
        else
        {
            $this->layout->content = View::make('admin.products.import_wait')
                ->with( 'id', $id );
        }
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

        $product->characteristics = array_merge(
            $product->characteristics,
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
