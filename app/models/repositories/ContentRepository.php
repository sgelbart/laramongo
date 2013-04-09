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
}
