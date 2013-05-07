<?php namespace Traits;

use Config;
use App;

trait Searchable
{
    protected $engine;

    public function searchEngineIndex()
    {
        if (! isset($this->engine)) {
            $engineName = Config::get('search_engine.engine');
            $this->engine = App::make($engineName);
        }

        $this->engine->indexObject($this);
    }
}
