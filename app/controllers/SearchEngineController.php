<?php

use Laramongo\SearchEngine\ElasticSearchEngine;

class SearchEngineController extends BaseController {

    public function search()
    {
        $query = Input::get('query');

        if ($query) {
            $collections = array('categories', 'contents', 'products');

            foreach ($collections as $collection) {
                $es = new ElasticSearchEngine();

                $es->connect();

                $es->prepareIndexationPath($collection);

                $es->searchObject([
                    'size' => '5',
                    'query' => [
                        'fuzzy_like_this' => [
                            'ignore_tf' => false,
                            'like_text' => $query,
                            "max_query_terms" => 25,
                            "min_similarity" => 0.3
                        ]
                    ]
                ]);

                $$collection = $es->getResultBy($collection);
            }

            $this->layout->content = View::make('searchengine.search')
                ->with('products', $products)
                ->with('contents', $contents)
                ->with('categories', $categories);
        } else {
            return Redirect::action('HomeController@index');
        }
    }

    /**
     * Similar to search but to be used with ajax
     *
     * @return Response
     */
    public function quickSearch()
    {
        $query = Input::get('query');

        if ($query) {
            $collections = array('categories', 'contents', 'products');

            foreach ($collections as $collection) {
                $es = new ElasticSearchEngine();

                $es->connect();

                $es->prepareIndexationPath($collection);

                $es->searchObject([
                    'size' => '5',
                    'query' => [
                        'fuzzy_like_this' => [
                            'ignore_tf' => false,
                            'like_text' => $query,
                            "max_query_terms" => 25,
                            "min_similarity" => 0.3
                        ]
                    ]
                ]);

                $$collection = $es->getResultBy($collection);
            }

            return View::make('searchengine.quicksearch', compact('products','contents','categories') );
        }
    }

}
