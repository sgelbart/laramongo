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

        if(! $product || ! $product->isVisible())
        {
            return Redirect::action('HomeController@index')
                ->with( 'flash', 'Produto não encontrada' );
        }

        $category = $product->category();

        if($product->conjugated)
        {
            return $this->showConjugated($product, $category);
        }

        // For non ajax requests, return the layout with the view embeded
        $this->layout->content = View::make('products.show')
            ->with( 'product', $product )
            ->with( 'category', $category );
    }

    protected function showConjugated( $product, $category )
    {
        $query = ['lm'=>['$in'=>$product->conjugated]];

        $conjProducts = Product::where($query);

        $this->layout->content = View::make('products.show_conjugated')
            ->with( 'product', $product )
            ->with( 'category', $category )
            ->with( 'conjProducts', $conjProducts );
    }
}
