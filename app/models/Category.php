<?php

class Category extends BaseModel {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'categories';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
    );

    /**
     * Path where category images will be stored
     *
     * @var string
     */
    private $images_path = '../public/assets/img/categories';

}
