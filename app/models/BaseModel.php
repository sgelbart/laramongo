<?php

use Zizaco\LmongoOrm\MongoModel;

abstract class BaseModel extends MongoModel
{
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = null;

    /**
     * Error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    public $errors;

    /**
     * Save the model to the database if it's valid
     *
     * @return bool
     */
    public function save()
    {
        if( $this->isValid() )
        {
            return parent::save();
        }
        else
        {
            return false;
        }
    }

    /**
     * Verify if the model is valid
     *
     * @return bool
     */
    public function isValid()
    {
        if(! is_array(static::$rules) )
            return true;

        $validator = Validator::make(
            $this->attributes,
            static::$rules
        );

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }
        else
        {
            return true;
        }
    }
}
