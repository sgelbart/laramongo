<?php namespace Zizaco\LmongoOrm;

use LMongo;

class MongoModel
{
    /**
     * The connection name for the model.
     *
     * @var LMongo\Database
     */
    protected $connection;

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'temporary';

    /**
     * The primary key for the model. Will become the _id 
     * in MongoDB document.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $original = array();

    /**
     * Once you put at least one string in this array, only
     * the attributes especified here will be changed
     * with the setAttributes method.
     *
     * @var array
     */
    public $massAssignment = array();

    /**
     * Save the model to the database.
     *
     * @return bool
     */
    public function save()
    {
        $preparedAttr = $this->prepareMongoAttributes( $this->attributes );

        $result = $this->collection()
            ->save( $preparedAttr, array("w" => 1) );

        if(isset($result['ok']) && $result['ok'] )
        {
            $this->parseDocument($this->attributes);
            $this->cleanAttribute('_id');
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete the model from the database
     *
     * @return bool
     */
    public function delete()
    {
        $preparedAttr = $this->prepareMongoAttributes( $this->attributes );

        $result = $this->collection()
            ->remove( $preparedAttr );

        if(isset($result['ok']) && $result['ok'] )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Find one document by id or by query array
     *
     * @param  mixed  $id
     * @param  array  $fields
     * @return Zizaco\LmongoOrm\MongoModel
     */
    public static function first($id = array(), $fields = array())
    {
        $instance = new static;

        // Get query array
        $query = $instance->prepareQuery($id);

        // If fields specified then prepare Mongo's projection
        if(! empty($fields))
            $fields = $instance->prepareProjection($fields);

        // Perform Mongo's findOne
        $document = $instance->collection()->findOne( $query, $fields );

        // If the response is correctly parsed return it
        if( $instance->parseDocument( $document ) )
        {
            return $instance;
        }
        else
        {
            return false;
        }
    }

    /**
     * Find one document by id or by query array. Returns
     * a single model if only one document matched the
     * criteria, or a OrmCursor if more than one.
     *
     * @param  mixed  $id
     * @param  array  $fields
     * @return mixed
     */
    public static function find($id = array(), $fields = array())
    {
        $result = static::where( $id, $fields );
        if( $result->count() == 1 )
        {
            $result->rewind();
            return $result->current();
        }
        else{
            return $result;
        }
    }

    /**
     * Find documents from the collection within the query
     *
     * @param  array  $query
     * @param  array  $fields
     * @return Zizaco\LmongoOrm\OrmCursor
     */
    public static function where($query = array(), $fields = array())
    {
        $instance = new static;

        // Get query array
        $query = $instance->prepareQuery($query);

        // If fields specified then prepare Mongo's projection
        if(! empty($fields))
            $fields = $instance->prepareProjection($fields);

        // Perform Mongo's find and returns iterable cursor
        $cursor =  new OrmCursor(
            $instance->collection()->find( $query, $fields ),
            get_class($instance)
        );

        return $cursor;
    }

    /**
     * Find "all" documents from the collection
     *
     * @param  array  $fields
     * @return Zizaco\LmongoOrm\OrmCursor
     */
    public static function all( $fields = array() )
    {
        return static::where( array(), $fields );
    }

    /**
     * Parses a BSON document array into model attributes.
     * Returns true on success.
     *
     * @param array $doc
     * @return bool
     */
    public function parseDocument( $doc )
    {
        if(! is_array($doc) )
            return false;

        try{
            // Grab the primary key
            if(isset($doc['_id']))
            {
                $pkey = (string)$doc['_id'];
                $this->setAttribute($this->primaryKey, $pkey);
                unset($doc['_id']);
            }

            // For each attribute, feed the model object
            foreach ($doc as $field => $value) {
                $this->setAttribute($field, $value);
            }

            // Define this attributes as the original
            $this->original = $this->attributes;

            // Returns success
            return true;
        }
        catch( Exception $e )
        {
            // Returns fail;
            return false;
        }
    }

    /**
     * Prepare query array for the given id or for the
     * given array.
     *
     * @param  mixed  $id
     * @return array
     */
    protected function prepareQuery($id)
    {
        if (! is_array($id))
        {
            // If not an array, then search by _id
            $id = array( 'id' => $id );
        }
        
        // Prepare query array with attributes
        $query = $this->prepareMongoAttributes($id);

        return $query;
    }

    /**
     * Prepare attributes to be used in MongoDb.
     * especially the _id.
     *
     * @param array $attr
     * @return array
     */
    private function prepareMongoAttributes($attr)
    {
        // Translate the primary key field into _id
        if( isset($attr[$this->primaryKey]) )
        {
            // If its a 24 digits hexadecimal, then it's a MongoId
            if (strlen($attr[$this->primaryKey]) == 24 && ctype_xdigit($attr[$this->primaryKey]))
            {
                $attr['_id'] = new \MongoId( $attr[$this->primaryKey] );   
            }
            elseif(is_numeric($attr[$this->primaryKey]))
            {
                $attr['_id'] = (int)$attr[$this->primaryKey];
            }
            else{
                $attr['_id'] = $attr[$this->primaryKey];   
            }
            unset($attr[$this->primaryKey]);
        }

        return $attr;
    }

    /**
     * Prepare Mongo's projection
     *
     * @param  array  $fields
     * @return array
     */
    protected function prepareProjection($fields)
    {
        // Prepare fields array for mongo query
        $fields = array_flip( $fields );
        foreach ($fields as $field => $value) {
            $fields[$field] = 1;
        }

        return $fields;
    }

    /**
     * Returns the LMongo database object (the connection)
     *
     * @return LMongo\Database
     */
    protected function db()
    {
        if( $this->connection == null )
        {
            $this->connection = LMongo::connection();
        }

        return $this->connection;
    }

    /**
     * Returns the LMongo collection object
     *
     * @return LMongo\Database
     */
    protected function collection()
    {
        return $this->db()->{$this->collection};
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        if ($inAttributes)
        {
            return $this->attributes[$key];
        }
        elseif ($key == 'attributes')
        {
            return $this->attributes;
        }
        else
        {
            return null;
        }
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Set the model attributes using an array
     *
     * @param  array   $input
     * @return void
     */
    public function setAttributes( $input )
    {
        foreach ($input as $key => $value) {
            if( empty($this->massAssignment) or in_array($key,$this->massAssignment) )
            {
                $this->setAttribute( $key, $value );
            }                
        }
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function cleanAttribute($key)
    {
        unset( $this->attributes[$key] );
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute exists on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Conver the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
