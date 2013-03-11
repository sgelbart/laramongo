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
        'category'     => 'required',
    );

    /**
     * Setable with the setAttributes method
     */
    public $massAssignment = array();

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
            Category::activate( $this->category );

        return $result;
    }
}
