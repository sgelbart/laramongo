<?php namespace Laramongo\SearchEngine;

abstract class SearchEngine
{
    abstract public function indexObject($object);

    /**
     * Maps the characteristics and fields contained within a category to
     * the searchEngine in order to be used as facets later
     * 
     * @param  Category $category A Category object
     * @return bool Success
     */
    abstract public function mapCategory($category);

    abstract public function searchObject();

    /**
     * Performs a facet search within a category. The search will be
     * performed in all the Products that contain the 'category' attribute
     * equals to the $category attribute passed to the method.
     * The facets should be build based in the category characteristics
     * 
     * @param  Category $category The product category there the search should be performed.
     * @param  array $filter   Should contain the chosen values to the facets given before.
     * @return boolean Success
     */
    abstract public function facetSearch($category, $filter = array());

    /**
     * Return result of the search query by type (Content, product, etc...)
     * 
     * @param  string $type name of collections to filtering result
     * @return array
     */
    abstract public function getResultBy($type);

    /**
     * Return the facet results of the last facetSearch
     * 
     * @return array
     */
    abstract public function getFacetResult();

    /**
     * Return the RAW result of the last search query performed by the search engine
     * 
     * @return array
     */
    abstract public function getRawResult();
}
