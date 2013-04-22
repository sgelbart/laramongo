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

        if( $view == 'relate_products' && strlen($search) > 5 && strstr($search,',') )
        {
            return View::make('search.mass_relate')
                ->with( 'search', $search )
                ->with( 'aditional_id', $aditional_id);
        }
        elseif( strlen($search) > 0 )
        {
            $products = $this->productRepo->search( $search )->limit(10);
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
