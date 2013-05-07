<?php

class ShopContent extends Content {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'contents';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
        'address' => 'required',
        'cep' => 'required',
        'description' => 'required'
    );
}
