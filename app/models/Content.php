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
        'slug' => 'required',
        'kind' => 'required',
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public static $factory = array(
        'name' => 'text',
    );

    /**
     * The products attached to the content
     */
    public function products()
    {
        return $this->referencesMany('Product','products');
    }

    /**
     * Determines if a content is visible or not. This takes a decision
     * assembling the following facts:
     * - hidden is not any sort of 'true'
     * - content has an _id
     */
    public function isVisible()
    {
        return 
            $this->hidden == false &&
            $this->approved == true &&
            $this->_id != false;
    }

    /**
     * Simply set the hidden attribute to true
     */
    public function hide()
    {
        $this->hidden = true;
    }

    /**
     * Simply unset the hidden attribute
     */
    public function unhide()
    {
        unset($this->hidden);
    }

    /**
     * Polymorph into ArticleContent if the kind is equals
     * to 'article'
     *
     * return mixed $instance
     */
    public function polymorph( $instance )
    {
        if( $instance->kind == 'article' )
        {
            $article = new ArticleContent;

            $article->parseDocument( $instance->attributes );
            return $article;
        }
        elseif( $instance->kind == 'video' )
        {
            $video = new VideoContent;

            $video->parseDocument( $instance->attributes );
            return $video;
        }
        else
        {
            return $instance;
        }
    }

    /**
     * Overwrites the setAttribute method in order to
     * explode a string into array before setting the
     * tags attribute
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if($key == 'tags')
        {
            if(is_string($value))
            {
                $value = array_map('trim',explode(",",strtolower($value)));
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Overwrited save method in order to insert the
     * tags to the tag collection
     *
     * @param $force Force save even if the object is invalid
     * @return bool
     */
    public function save($force = false)
    {
        if( $this->isValid() || $force )
        {
            $result = parent::save();
            
            if( $result )
                $this->insertTags();

            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * Perform a primitive MongoDB insert in order to
     * be as fast as possible. If the tag is duplicated
     * it will not be inserted, which is not a problem
     *
     * @return null
     */
    private function insertTags()
    {
        $tagsToInsert = array();

        foreach ($this->tags as $tag) {
            $tagsToInsert[] = ['_id'=>$tag];
        }

        // batchInsert with write concern as 'Unacknowledged' (w=0)
        $connector = new Zizaco\Mongoloid\MongoDbConnector;
        $connector->getConnection()->db->tags->batchInsert($tagsToInsert, ["w" => 0]);
    }

}
