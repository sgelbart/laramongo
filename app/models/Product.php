<?php

class Product extends BaseModel
{
    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'products';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'name'      => 'required',
        'family'     => 'required',
    );

    /**
     * Setable with the setAttributes method
     */
    public $massAssignment = array();

    /**
     * Verify if the model is valid and if the category exists
     *
     * @return bool
     */
    public function isValid()
    {
        if( parent::isValid() )
        {
            // Check the cache if this has already been compared within 2 minutes
            if( Category::exists($this->family) )
            {
                return true;
            }
            else
            {
                $error_message = "Categoria '".$this->family."' não encontrada. ".
                                 " <b><a href='".
                                 URL::action('Admin\CategoriesController@index').
                                 "'>Gerenciar Categorias</a></b>";

                $this->errors = new Illuminate\Support\MessageBag( (array)$error_message );

                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Return image URL
     *
     * @return string
     */
    public function imageUrl( $img = 1, $size = 300 )
    {
        if( file_exists(app_path().'/../public/assets/img/products/'.$this->id.'_'.$img.'_'.$size.'.jpg') )
        {
            return URL::to('assets/img/products/'.$this->id.'_'.$img.'_'.$size.'.jpg');
        }
        else
        {
            return URL::to('assets/img/products/default.png');
        }
    }

    /**
     * Make sure to actvate product category
     *
     * @return bool
     */
    public function save()
    {
        $result = parent::save();

        // Should activate the category
        if($result)
            Category::activate( $this->family );

        return $result;
    }
}
