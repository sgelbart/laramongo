<?php

use Laramongo\SearchEngine\ElasticSearchEngine;

class SearchEngineController extends BaseController {
    public function search()
    {
        $query = Input::get('query');

        $es = new ElasticSearchEngine();

        $es->searchObject($query);

        $products = $es->getResultBy('products');
        $contents = $es->getResultBy('contents');
        $categories = $es->getResultBy('categories');

        $this->layout->content = View::make('searchengine.search')
            ->with('products', $products)
            ->with('contents', $contents)
            ->with('categories', $categories);
    }
}
