<?php

use Laramongo\SearchEngine\ElasticSearchEngine;

class SearchEngineController extends BaseController {
    public function search()
    {
        $query = Input::get('query');

        if ($query) {
            $es = new ElasticSearchEngine();

            // $es->searchObject("*" . $query . "*");

            $collections = array('products', 'contents', 'categories');

            $es->connect();

            foreach ($collections as $collection) {
                $es->prepareIndexationPath($collection);

                $es->searchObject([
                    'query' => [
                        'fuzzy_like_this' => [
                            'like_text' => $query,
                            "max_query_terms" => 5,
                            "min_similarity" => 0.1
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
 }
