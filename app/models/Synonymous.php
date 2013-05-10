<?php

class Synonymous extends BaseModel
{

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'synonyms';

    /**
     * Factory definition
     *
     * @var array
     */
    public static $factory = array(
        'related_word' => array()
    );

    public $guarded = array(
        '_PUT',
        '_method'
    );

    public static $rules = array(
        'word' => 'required',
        'related_word' => 'required'
    );

    public function save($force=false)
    {
        if( $this->isValid() )
        {
            if(parent::save( $force ))
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function setAttribute($key, $value)
    {
        if($key == 'related_word')
        {
            if(is_string($value))
            {
                $value = array_map('trim',explode(",",strtolower($value)));
            }
        }

        return parent::setAttribute($key, $value);
    }
}
