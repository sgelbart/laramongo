<?php namespace Laramongo\SearchEngine;

abstract class SearchEngine
{
    abstract public function indexObject($object);

    abstract public function searchObject();

    /**
     * Performs a facet search within a category. The search will be
     * performed in all the Products that contain the 'category' attribute
     * equals to the $category attribute passed to the method.
     * The facets should be build based in the category characteristics
     * 
     * @param  array $facets   The facets in the array format.
     * @param  array $category The product category there the search should be performed.
     * @param  array $filter   Should contain the chosen values to the facets given before.
     * @return boolean Success
     */
    abstract public function facetSearch($facets, $category, $filter = array());
}
