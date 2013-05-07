<?php namespace Laramongo\SearchEngine;

abstract class SearchEngine
{
    abstract public function indexObject($object);
}
