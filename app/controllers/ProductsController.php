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
        $this->layout->content =
            Template::make('products.show',
                array (
                    'product' => $product,
                    'category'=> $category
                )
            );
    }

    protected function showConjugated( $product, $category )
    {
        $this->layout->content =
            Template::make('products.show_conjugated',
                array(
                    'product' => $product,
                    'category'=> $category,
                    'conjProducts' => $product->products()
                )
            );
    }
}
