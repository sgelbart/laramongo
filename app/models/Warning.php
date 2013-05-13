<?php

class Warning extends BaseModel
{

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'warnings';

    /**
     * Factory definition
     *
     * @var array
     */
    public static $factory = array(
        'keyword' => 'bacon'
    );

    public $guarded = array(
        '_PUT',
        '_method'
    );

    public static $rules = array(
        'keyword' => 'required'
    );

    public static function setWarning($keyword)
    {
        $warn = new Warning();
        $warn->keyword = $keyword;
        $warn->lastSearch = getdate();

        $warn->save();
    }
}
