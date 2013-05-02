<?php

class ArticleContent extends Content {

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
        'slug' => 'required',
        'type' => 'required',
        'article' => 'required',
    );

    public function render()
    {
        return $this->article;
    }

}
