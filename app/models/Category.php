<?php

use Illuminate\Support\MessageBag;

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

    /**
     * Verify if the model is valid
     *
     * @return bool
     */
    public function isValid()
    {
        $valid = parent::isValid();

        if( $valid )
        {
            $exists = Category::where('name',$this->name)->get()->count();

            if( $exists )
            {
                $this->errors = new MessageBag(['Já existe uma categoria com esse nome']);
                return false;
            }
            else
            {
                return true;
            }
        }

        return false;
    }

}
