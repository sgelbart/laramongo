<?php

class SearchController extends BaseController {

    /**
     * Returns a Json containing a list of products following
     * the search criterea.
     *
     * @return Response
     */
    public function products()
    {
        $search = Input::get('search');

        if( strlen($search) > 0 )
        {
            $query = [ '$or'=> [
                ['name'=> new \MongoRegex('/^'.$search.'/i')],
                ['lm'=> new \MongoRegex('/^'.$search.'/i')]
            ]];

            $products = Product::where($query, ['_id','name'])
                ->limit(20);
        }
        else
        {
            $products = [];
        }

        return View::make('search.products')
                ->with( 'products', $products );
    }
}
