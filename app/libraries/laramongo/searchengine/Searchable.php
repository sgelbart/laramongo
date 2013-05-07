<?php namespace Laramongo\SearchEngine;

interface Searchable
{
    /**
     * Insert a index at Search engine
     * @return boolean
     */
    public function searchEngineIndex();
}
