<?php namespace Laramongo\StoresProductsIntegration;

class StoreProduct extends \BaseModel {

    public static $factory = [];

    protected $collection = 'temp_storesProductsIntegration';

    /**
     * TODO:
     * 
     * You should overwrite the save() method in order to use the PriceCalculator and to save the price
     * into the product by region
     */

}
