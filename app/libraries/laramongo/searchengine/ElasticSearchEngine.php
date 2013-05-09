<?php namespace Laramongo\SearchEngine;

use \ElasticSearch\Client, Config;

class ElasticSearchEngine extends SearchEngine
{
    /**
     * Client of Elastic Search instance
     *
     * @var ElasticSearch\Client
     */
    public $es;

    /**
     * Object that is going to be indexed
     *
     * @var Model
     */
    protected $object;

    /**
     * Result the searchObject function
     * @var array
     */
    protected $searchResult;

    /**
     * Create the connection with elastic search
     *
     * @return null
     */
    public function connect()
    {
        if (! isset($this->es)) {
            $this->es = Client::connection(
                Config::get('search_engine.settings.elastic_search.connection_url')
            );
        }
    }

    /**
     * Insert the elastic_search index
     *
     * @return boolean
     */
    public function indexObject($object)
    {
        if (Config::get('search_engine.enabled')) {
            $this->object = $object;

            if(! $this->object->_id)
            {
                trigger_error("The object provided doens't have an _id. Make sure to save it in database first.");
            }

            $this->connect();

            $this->prepareIndexationPath($this->object->getCollectionName());

            $attributes = $this->object->getAttributes();
            unset($attributes['_id']);
            $this->es->index($attributes, $this->object->_id);
        }
    }

    /**
     * Maps the characteristics and fields contained within a category to
     * the ElasticSearch in order to be used as facets later
     *
     * @param  Category $category A Category object
     * @return bool Success
     */
    public function mapCategory($category)
    {
        if (Config::get('search_engine.enabled')) {
            $this->connect();

            $this->prepareIndexationPath('products');

            $characs = array();
            foreach ($category->characteristics() as $charac) {
                $characs['characteristics']['properties'][clean_case($charac->name)] = ['type'=>'string'];
            }

            $this->es->map(['properties'=>$characs]);
        }
    }

    /**
     * Search multiples types and return values
     *
     * @param  array or string $types the types used at Elastic Search
     * @param  string $query what you want to search
     * @return result
     */
    public function searchObject($query= '*:*')
    {
        if (Config::get('search_engine.enabled')) {
            $this->connect();

            $this->prepareIndexationPath(array('contents', 'products', 'categories'));

            $this->searchResult = $this->es->search($query);
        }
    }

    /**
     * Performs a facet search within a category. The search will be
     * performed in all the Products that contain the 'category' attribute
     * equals to the $category attribute passed to the method.
     * The facets should be build based in the category characteristics
     *
     * @param  array $facets   The facets in the array format. See: http://www.elasticsearch.org/guide/reference/api/search/facets/
     * @param  array $category The product category there the search should be performed.
     * @param  array $filter   Should contain the chosen values to the facets given before.
     * @return boolean Success
     */
    public function facetSearch($facets, $category, $filter = array())
    {
        if (Config::get('search_engine.enabled')) {
            $this->connect();

            $this->prepareIndexationPath('products');

            $this->searchResult = $this->es->search([
                'query' => [
                    'term'=>['category'=>$category]
                ],
                'facets' => $facets
            ]);
        }
    }

    /**
     * Return result of search query
     *
     * @param  string $type name of collections to filtering result
     * @return array
     */
    public function getResultBy($type)
    {
        $filteredResult = array();

        if ($this->searchResult['hits']) {
            foreach ($this->searchResult['hits']['hits'] as $indexed) {
                if ($indexed['_type'] == $type) {
                    $indexed['_source']['_id'] = $indexed['_id'];

                    $className = $this->getClassName($type);

                    $object = new $className();

                    $object->parseDocument( $indexed['_source'] );
                    $object = $object->polymorph( $object );

                    array_push($filteredResult, $object);
                }
            }
        } else {
            return false;
        }

        return $filteredResult;
    }

    /**
     * Return the facet results of the last facetSearch
     *
     * @return array
     */
    public function getFacetResult()
    {
        return array_get($this->getRawResult(), 'facets',[]);
    }

    /**
     * Return the RAW result of search query
     *
     * @return array
     */
    public function getRawResult()
    {
        return $this->searchResult;
    }

    /**
     * Prepare the index name used by elastic search
     * @param  string or array $types
     * @return null
     */
    public function prepareIndexationPath($types)
    {
        $this->es->setIndex(Config::get('search_engine.application_name'));
        $this->es->setType($types);
    }

    private function getClassName($name)
    {
        switch ($name) {
            case 'products':
                return 'Product';
                break;

            case 'categories':
                return 'Category';
                break;
            case 'contents':
                return 'Content';
                break;
        }
    }
}
