<?php namespace Laramongo\SearchEngine;

abstract class SearchEngine
{
    abstract public function indexObject($object);

    abstract public function searchObject();
}
