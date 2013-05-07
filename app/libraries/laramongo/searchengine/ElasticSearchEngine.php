<?php namespace Laramongo\SearchEngine;

use \ElasticSearch\Client, Config;

class ElasticSearchEngine extends SearchEngine
{
/**
     * Client of Elastic Search instance
     * @var Client
     */
    protected $es;

    /**
     * Create the connection with elastic search
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
     * @return boolean
     */
    public function indexObject($object)
    {
        if (Config::get('search_engine.enabled')) {
            $this->connect();

            $this->prepareIndexationPath();

            $this->es->index($this->getAttributes(), $this->_id);
        }
    }

    /**
     * Prepare the index name used by elastic search
     * @return null
     */
    protected function prepareIndexationPath()
    {
        $this->es->setIndex(Config::get('search_engine.application_name'));
        $this->es->setType($this->collection);
    }
}
