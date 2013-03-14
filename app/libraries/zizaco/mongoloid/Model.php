<?php namespace Zizaco\Mongoloid;

use MongoClient;

class Model
{
    /**
     * The connection name for the model.
     *
     * @var MongoDB
     */
    protected $connection;

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'temporary';

    /**
     * The database associated with the model.
     *
     * @var string
     */
    protected $database = 'mongoloid';

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
    public $fillable = array();

    /**
     * Save the model to the database.
     *
     * @return bool
     */
    public function save()
    {
        if (! $this->collection)
            return false;

        $preparedAttr = $this->prepareMongoAttributes( $this->attributes );

        $result = $this->collection()
            ->save( $preparedAttr, array("w" => 1) );

        if(isset($result['ok']) && $result['ok'] )
        {
            $this->parseDocument($this->attributes);
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
     * @return Zizaco\LmongoOdm\MongoModel
     */
    public static function first($id = array(), $fields = array())
    {
        $instance = new static;

        // Get query array
        $query = $instance->prepareQuery($id);

        // If fields specified then prepare Mongo's projection
        if(! empty($fields))
            $fields = $instance->prepareProjection($fields);

        // Perfodm Mongo's findOne
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
     * criteria, or a OdmCursor if more than one.
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
     * @return Zizaco\LmongoOdm\OdmCursor
     */
    public static function where($query = array(), $fields = array())
    {
        $instance = new static;

        // Get query array
        $query = $instance->prepareQuery($query);

        // If fields specified then prepare Mongo's projection
        if(! empty($fields))
            $fields = $instance->prepareProjection($fields);

        // Perfodm Mongo's find and returns iterable cursor
        $cursor =  new OdmCursor(
            $instance->collection()->find( $query, $fields ),
            get_class($instance)
        );

        return $cursor;
    }

    /**
     * Find "all" documents from the collection
     *
     * @param  array  $fields
     * @return Zizaco\LmongoOdm\OdmCursor
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
            $id = array( '_id' => $id );
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
        if( isset($attr['_id']) )
        {
            // If its a 24 digits hexadecimal, then it's a MongoId
            if ($this->isMongoId($attr['_id']))
            {
                $attr['_id'] = new \MongoId( $attr['_id'] );   
            }
            elseif(is_numeric($attr['_id']))
            {
                $attr['_id'] = (int)$attr['_id'];
            }
            else{
                $attr['_id'] = $attr['_id'];   
            }
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
     * Returns the database object (the connection)
     *
     * @return MongoDB
     */
    protected function db()
    {
        if( $this->connection == null )
        {
            $this->connection = new MongoClient();
        }

        return $this->connection->{$this->database};
    }

    /**
     * Returns the LMongo collection object
     *
     * @return MongoDB
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
     * Get all attributes from the model.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the _id.
     *
     * @return mixed
     */
    public function getMongoId()
    {
        return $this->getAttribute('_id');
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
    public function fill( $input )
    {
        foreach ($input as $key => $value) {
            if( empty($this->fillable) or in_array($key,$this->fillable) )
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
     * Returns the model instance as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }

    /**
     * Returns the model instance as an Array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Returns the referenced documents as objects
     */
    protected function referencesOne($model, $field)
    {
        return $model::where(array('_id'=>$this->$field));
    }

    /**
     * Returns the cursor for the referenced documents as objects
     */
    protected function referencesMany($model, $field)
    {
        $ref_ids = $this->$field;

        if (! isset($ref_ids[0]) )
            return array();

        if ($this->isMongoId($ref_ids[0]))
        {
            foreach ($ref_ids as $key => $value) {
                $ref_ids[$key] = new \MongoId($value);
            }
        }

        return $model::where(array('_id'=>array('$in'=>$ref_ids)));
    }

    /**
     * Return array of referenced documents as objects
     */
    protected function embedsMany($model, $field)
    {
        $documents = array();

        if(is_array($this->$field))
        {
            foreach ($this->$field as $document) {
                $instance = new $model;
                $instance->parseDocument( $document );
                $documents[] = $instance;
            }
        }

        return $documents;
    }

    /**
     * Attach a new document or id to an reference array
     * 
     * @param string $field
     * @param mixed $obj _id, document or model instance
     * @return void
     */
    public function attach($field, $obj)
    {
        if( is_a($obj,'Zizaco\Mongoloid\Model') )
        {
            $mongoId = $obj->getMongoId();
        }
        elseif( is_array($obj) )
        {
            if(isset($obj['id']))
            {
                $mongoId = $obj['id'];
            }
            elseif(isset($obj['_id']))
            {
                $mongoId = $obj['_id'];
            }
        }
        else
        {
            $mongoId = $obj;
        }

        if($mongoId != null)
        {
            $attr = (array)$this->getAttribute($field);
            $attr[] = $mongoId;
            $this->setAttribute($field, $attr);
        }
    }

    /**
     * Detach a document or id from an reference array
     * 
     * @param string $field
     * @param mixed $obj _id, document or model instance
     * @return void
     */
    public function detach($field, $obj)
    {
        if( is_a($obj,'Zizaco\Mongoloid\Model') )
        {
            $mongoId = $obj->getMongoId();
        }
        elseif( is_array($obj) )
        {
            if(isset($obj['id']))
            {
                $mongoId = $obj['id'];
            }
            elseif(isset($obj['_id']))
            {
                $mongoId = $obj['_id'];
            }
        }
        else
        {
            $mongoId = $obj;
        }

        if($mongoId != null)
        {
            $attr = (array)$this->getAttribute($field);
            
            foreach ($attr as $key => $value) {
                if((string)$value == (string)$mongoId)
                {
                    unset($attr[$key]);
                }
            }
            $this->setAttribute($field, $attr);
        }
    }

    /**
     * Embed a new document to an attribute
     * 
     * @param string $field
     * @param mixed $obj _id, document or model instance
     * @return void
     */
    public function embed($field, $obj)
    {
        if( is_a($obj,'Zizaco\Mongoloid\Model') )
        {
            $document = $obj->toArray();
        }
        else
        {
            $document = $obj;
        }

        if($document != null)
        {
            $attr = (array)$this->getAttribute($field);
            $attr[] = $document;
            $this->setAttribute($field, $attr);
        }
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
        // Set attribute
        $this->setAttribute($key, $value);    
    }

    public function __call($method, $parameters)
    {
        $value = isset($parameters[0]) ? $parameters[0] : null;

        if ('attachTo' == substr($method,0,8)) //
        {
            // Attach a new document or id to an reference array
            $field = strtolower(substr($method,8,1)).substr($method,9);
            $this->attach($field, $value);
        }
        elseif ('embedTo' == substr($method,0,7)) //
        {
            // Embed a new document or id to an reference array
            $field = strtolower(substr($method,7,1)).substr($method,8);
            $this->embed($field, $value);
        }
        else
        {
            trigger_error('Call to undefined method '.$method);
        }
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

    /**
     * Checks if a string is a MongoID
     * 
     * @param string $string String to be checked.
     * @return boolean
     */
    private function isMongoId($string)
    {
        // If its a 24 digits hexadecimal, then it's a MongoId
        return (is_string($string) && strlen($string) == 24 && ctype_xdigit($string));
    }
}
