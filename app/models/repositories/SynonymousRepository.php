<?php

class SynonymousRepository
{

    public $perPage = 10;

    /**
     * Return the ammount of pages that a cursor should
     * have considering the $this->perPage
     *
     * @param $cursor An OdmCursor
     * @return int Ammount of pages
     */
    public function pageCount( $cursor )
    {
        $count = $cursor->count();

        if(is_numeric($count) && $count > 0)
            return round($cursor->count()/$this->perPage);

        return 0;
    }

    /**
     * Return the $cursor paginated using the $perPage
     * attribute. A $page may be specified in order to
     * skip some of the product
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
     * Getting all synonymous
     *
     * @return array of Synonymous
     */
    public function getAll()
    {
        return Synonymous::all();
    }

    /**
     * Getting instance of Synonymous
     *
     * @return Synonymous object
     */
    public function newSym()
    {
        return new Synonymous();
    }

    /**
     * Getting Symnonymous by id
     * @param  number $id
     *
     * @return Synonymous object
     */
    public function findBy($id)
    {
        return Synonymous::first($id);
    }
}
