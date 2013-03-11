<?php

class ProductsController extends BaseController {

	/**
	 * Display the specified resource.
	 *
	 * @return Response
	 */
	public function show($id)
	{
        $product = Product::first($id);

        if(! $product)
        {
            return Redirect::action('HomeController@index')
                ->with( 'flash', 'Produto não encontrada' );
        }

        $category = Category::first( array('name'=>$product->family) );

        // For non ajax requests, return the layout with the view embeded
        $this->layout->content = View::make('products.show')
            ->with( 'product', $product )
            ->with( 'category', $category );
	}
}
