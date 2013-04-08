<?php

class Content extends BaseModel {

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
        'kind' => 'required',
    );

    /**
     * The products attached to the content
     */
    public function products()
    {
        return $this->referencesMany('Product','products');
    }

    /**
     * Polymorph into ArticleContent if the kind is equals
     * to 'article'
     *
     * return mixed $instance
     */
    protected function polymorph( $instance )
    {
        if( $instance->kind = 'article' )
        {
            $article = new ArticleContent;

            $article->parseDocument( $instance->attributes );
            return $article;
        }
        else
        {
            return $instance;
        }
    }

}
