<?php namespace Traits;

use Config;
use App;

trait Searchable
{
    public function searchEngineIndex()
    {
        if (Config::get('search_engine.enabled')) {
            $engineName = Config::get('search_engine.engine');
            $searchEngine = App::make($engineName);

            $searchEngine->indexObject($this);
        }
    }
}
