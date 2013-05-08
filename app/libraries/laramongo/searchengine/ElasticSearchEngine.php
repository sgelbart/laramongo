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
     * Create the connection with elastic search
     *
     * @return null
     */
    protected function connect()
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

            $this->prepareIndexationPath();

            $attributes = $this->object->getAttributes();
            unset($attributes['_id']);
            
            $this->es->index($attributes, $this->object->_id);
        }
    }

    /**
     * Prepare the index name used by elastic search
     *
     * @return null
     */
    protected function prepareIndexationPath()
    {
        $this->es->setIndex(Config::get('search_engine.application_name'));
        $this->es->setType($this->object->getCollectionName());
    }
}
