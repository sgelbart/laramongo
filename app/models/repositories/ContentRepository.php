<?php

class ContentRepository
{
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
}
