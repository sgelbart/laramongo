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

            $this->connect();

            $this->prepareIndexationPath($this->object->getCollectionName());

            $attributes = $this->object->getAttributes();
            unset($attributes['_id']);

            $this->es->index($attributes, $this->object->_id);
        }
    }

    /**
     * Search multiples types and return values
     * @param  array or string $types the types used at Elastic Search
     * @param  string $query what you want to search
     * @return result
     */
    public function searchObject()
    {
        if (Config::get('search_engine.enabled')) {
            $this->connect();

            $this->prepareIndexationPath(array('contents', 'products', 'categories'));

            $this->searchResult = $this->es->search('*:*');
        }
    }

    /**
     * Return result of search query
     * @param  string $type name of collections to filtering result
     * @return array
     */
    public function getResultBy($type)
    {
        $filteredResult = array();

        foreach ($this->searchResult['hits']['hits'] as $indexed) {
            if ($indexed['_type'] == $type) {
                array_push($filteredResult, $indexed['_source']);
            }
        }

        return $filteredResult;
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
}
