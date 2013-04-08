<?php

abstract class Content extends BaseModel {

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
    );

    /**
     * The products attached to the content
     */
    public function products()
    {
        return $this->referencesMany('Product','products');
    }

}
