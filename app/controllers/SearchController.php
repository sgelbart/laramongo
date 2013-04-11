<?php

class SearchController extends BaseController {

    protected $productRepo;

    function __construct( ProductRepository $productRepo )
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Returns a Json containing a list of products following
     * the search criterea.
     *
     * @return Response
     */
    public function products($view)
    {
        $search = Input::get('search');
        $aditional_id = Input::get('aditional_id');

        if( strlen($search) > 0 )
        {
            $products = $this->productRepo->search( $search );
        }
        else
        {
            $products = [];
        }

        return View::make('search.'.$view)
                ->with( 'products', $products )
                ->with( 'aditional_id', $aditional_id);
    }
}
