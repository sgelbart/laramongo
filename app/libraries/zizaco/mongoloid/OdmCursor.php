<?php namespace Zizaco\Mongoloid;

class OdmCursor implements \Iterator
{
    /**
     * Model class that will be returned when iterate
     *
     * @var string
     */
    protected $model;

    /**
     * The MongoCursor used to interact with db
     *
     * @var MongoCursor
     */
    protected $cursor;

    /**
     * Iterator position (to be used with foreach)
     *
     * @var integer
     */
    private $position = 0;

    /**
     * OdmCursor constructor. The mongo cursor and the
     * model should be provided
     *
     * @param $cursor MongoCursor
     * @param $model string
     * @return void
     */
    public function __construct( $cursor, $model )
    {
        $this->cursor = $cursor;

        $this->model = $model;

        $this->position = 0;
    }

    /**
     * Calls the MongoCursor method if it exists.
     * This makes possible to run methods like limit, skip,
     * orts, and others.
     *
     * @param $name string
     * @param $args array
     */
    public function __call($name, $args)
    {
        if( method_exists( $this->cursor, $name) )
        {
            // Calls the method in MongoCursor
            $result = call_user_func_array( array($this->cursor, $name), $args);

            // In case of sort, limit and other methods of the cursor
            // that return itself (for chained method calls), should
            // return $this (OdmCursor object) instead of MongoCursor.
            if(is_object($result) && get_class($result) == 'MongoCursor')
            {
                return $this;
            }
            else
            {
                return $result;
            }
        }
    }

    /**
     * Returns the MongoCursor object
     *
     * @return MongoCursor
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * Iterator interface rewind (used in foreach)
     *
     */
    function rewind() {
        $this->cursor->rewind();
        $this->position = 0;
    }

    /**
     * Iterator interface current. Return a model object
     * with cursor document. (used in foreach)
     *
     * @return mixed
     */
    function current()
    {
        $document = $this->cursor->current();

        $model = new $this->model();

        $document = $this->cursor->current();

        if( $model->parseDocument( $document ) )
        {
            return $model;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convert the cursor instance to an array.
     *
     * @return array
     */
    public function toArray( $documentsTooArray = true )
    {
        $result = array();

        $this->limit(20);
        foreach($this as $document)
        {
            if( $documentsTooArray )
            {
                $result[] = $document->getAttributes();
            }
            else
            {
                $result[] = $document;
            }
        }

        return $result;
    }

    /**
     * Iterator key method (used in foreach)
     *
     */
    function key() {
        return $this->position;
    }

    /**
     * Iterator next method (used in foreach)
     *
     */
    function next() {
        ++$this->position;
        $this->cursor->next();
    }

    /**
     * Iterator valid method (used in foreach)
     *
     */
    function valid() {
        return $this->cursor->valid();
    }

    /**
     * Conver the cursor to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        $result = '';

        $this->limit(20);
        foreach($this as $document)
        {
            $result .= (string)$document;
        }

        $result = '['.$result.']';

        return $result;
    }
}
