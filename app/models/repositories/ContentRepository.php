<?php

class ContentRepository
{

    public $perPage = 6;

    /**
     * Should return a cursor of Contents. If there is
     * a term of the search, bring only content with that
     * name. If a kind is specified, brings only content
     * of that type
     *
     * @param $terms String to search in title of content
     * @param $kind To filter the content kind
     * @return OdmCursor The results
     */
    public function search( $terms = null, $kind = null )
    {
        if(! $terms)
        {
            $contents = Content::all();
        }
        else
        {
            $query = ['name'=> new \MongoRegex('/^'.$terms.'/i')];
            $contents = Content::where($query);
        }

        return $contents;
    }

    /**
     * Return the ammount of pages that a cursor should
     * have considering the $this->perPage
     *
     * @param $cursor An OdmCursor
     * @return int Ammount of pages
     */
    public function pageCount( $cursor )
    {
        return round($cursor->count()/$this->perPage);
    }

    /**
     * Return the $cursor paginated using the $perPage
     * attribute. A $page may be specified in order to
     * skip some of the content
     *
     * @param $cursor The cursor to be paginated
     * @param $page The page that are gonna be returned
     * @return OdmCursor Paginated cursor.
     */
    public function paginate( $cursor, $page = null )
    {
        if(! $page)
            $page = 1;

        return $cursor->limit( $this->perPage )
            ->skip( ($page-1)*$this->perPage );
    }

    /**
     * Saves a new instance into the database and return
     * the save result (that will run the isValid) since
     * Content extends from the BaseModel.
     *
     * @param $instance Non saved Content instance
     * @param $image A image that has been uploaded PS: Get it with Input::file('name')
     * @return Boolean The result of the instance save() method
     */
    public function createNew( Content &$instance, $image = null )
    {
        // Since is a NEW content. Clear the _id if it exists
        unset($instance->_id);
        
        // Apply the polymorph manually before saving
        // This way the specific validation will play their role
        // before saving the model
        $instance = $instance->polymorph( $instance );

        // Save the instance
        $result = $instance->save();

        // If there is an image. Attach it!
        if( $result && $image && $instance instanceof ImageContent )
        {
            $instance->attachUploadedImage( $image );
        }

        return $result;
    }

    /**
     * Find an article by slug
     *
     * @param $slug The slug of the article
     * @return Content the first article found
     */
    public function FindBySlug( $slug )
    {
        return Content::first(['slug'=>$slug]);
    }

    /**
     * Get the existing tags that begins with $term
     *
     * @param $term To tag to search too
     * @return Array of terms ready to be converted as json
     */
    public function existentTags( $term )
    {
        $connection = new Zizaco\Mongoloid\MongoDbConnector;
        $db = $connection->getConnection()->db;

        $tags = $db->tags->find( ['_id'=> new \MongoRegex('/^'.$term.'/i')] );

        foreach ($tags as $tag) {
            $result[] = ['id'=>$tag['_id'], 'label'=>$tag['_id'], 'value'=>$tag['_id']];
        }

        return $result;
    }

    /**
     * Returns one content with the $id
     *
     * @param $id Id or query
     * @return Content
     */
    public function first( $id )
    {
        return Content::first($id);
    }

    /**
     * Returns the result of an $instance->save()
     *
     * @param $instance Content instance to be saved
     * @param $image A image that has been uploaded PS: Get it with Input::file('name')
     * @return Boolean save() result
     */
    public function update( Content &$instance, $image = null )
    {
        // If there is an image. Attach it!
        if( $image && $instance instanceof ImageContent )
        {
            $instance->attachUploadedImage( $image );
        }

        return $instance->save();
    }

    /**
     * Simply attach a product to a Content instance
     *
     * @param $instance Content instance
     * @param $product The Product instance or id
     * @return Boolean Success
     */
    public function relateToProduct( Content &$instance, $product )
    {
        if(is_numeric($product))
            $product = (int)$product;
        
        $instance->attachToProducts($product);
        return $instance->save();
    }
}
