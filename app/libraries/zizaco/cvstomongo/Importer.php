<?php namespace Zizaco\CsvToMongo;

use Keboola\Csv\CsvFile;
use Illuminate\Support\MessageBag;

class Importer
{
    /**
     * Model class that will be imported
     *
     * @var string
     */
    protected $model;

    /**
     * The Keboola CVS object
     *
     * @var Keboola\Csv\CsvFile
     */
    protected $keboola;

    /**
     * Errors that happened during the import
     *
     * @var array
     */
    protected $errors;

    /**
     * Successfuly imported models
     *
     * @var array
     */
    protected $success;

    /**
     * Array of attributes that are not characteristics. This attributes
     * will not be embedded in the details attribute.
     */
    protected $non_characteristic_keys = [
        '_id','name','description','small_description','category','products',
    ];

    /**
     * The file and the model/collection that should be imported
     *
     * @param $file string
     * @param $model string
     * @return void
     */
    public function __construct( $file, $model, $delimiter = ';' )
    {
        $this->keboola = new CsvFile( $file, $delimiter );

        $this->model = $model;
    }

    /**
     * Imports the CSV file contents into the model
     * collection. Performs model validation in each
     * line.
     *
     * @return void
     */
    public function import( $category, $conjugated = false )
    {
        $headers = array();
        $conjugatedCount = 1;

        foreach( $this->keboola as $line )
        {
            if( empty($headers) )
            {
                $headers = $line;
            }
            else
            {
                $instance = new $this->model();
                $attributes = array_combine( $headers, $this->treatLine($line) );

                try{

                    foreach ($attributes as $key => $value) {

                        if(! in_array($key, $this->non_characteristic_keys))
                        {
                            $attributes['details'][$key] = $value;
                            unset($attributes[$key]);
                        }

                        // Conjugated
                        if($key == 'products')
                        {
                            unset($attributes[$key]);
                            $conjugatedArray = array_map('trim',explode(".",$value));

                            foreach ($conjugatedArray as $i => $lm) {
                                if(is_numeric($lm))
                                {
                                    $conjugatedArray[$i] = (int)$lm;
                                }
                            }

                            $attributes['conjugated'] = $conjugatedArray;
                        }
                    }

                    if( $instance->parseDocument(
                        $attributes
                    ))
                    {
                        // Set the leaf category where that product belongs
                        $instance->category = $category;

                        if($instance->_id)
                        {
                            $instance->save(true);
                        }
                        elseif($conjugated)
                        {
                            $generatedId = 'CJ'.
                                substr(microtime(),-8).
                                str_pad($conjugatedCount,3,'0',STR_PAD_LEFT);

                            $conjugatedCount++;

                            $instance->_id = $generatedId;

                            $instance->save();
                        }
                        else
                        {
                            $instance->errors = new MessageBag(['_id','Produto sem LM']);
                        }

                        if( ! $instance->errors )
                        {
                            $this->success[] = $instance;
                        }
                        else
                        {
                            $this->errors[] = $instance;
                        }
                    }
                }
                catch(\Exception $e)
                {
                    $instance = new $this->model();

                    $instance->category = $category;

                    if($attributes['_id'])
                        $instance->_id = $attributes['_id'];

                    if($attributes['name'])
                        $instance->name = $attributes['name'];

                    $instance->errors = new MessageBag(['Product is invalid:', 'Exception - '.$e->getMessage() ]);

                    $this->errors[] = $instance;
                        
                }
            }
        }
    }

    /**
     * Treat the input data before feeding model
     *
     * @param $line array
     * @return array
     */
    private function treatLine( $line )
    {
        foreach ($line as $key => $value) {

            $value = trim( $value );

            // Replaces brazilian , to .
            if( $this->keboola->getDelimiter() != ',' )
            {
                $value = str_replace( ',','.',$value );
            }

            // Remove currency symbols
            $value = str_replace( 'R$','',$value );

            // Correct data type
            if( is_numeric($value) )
            {
                $value = (float)$value;
            }
            else
            {
                $value = ucfirst($value);
            }

            $line[$key] = utf8_encode($value);
        }

        return $line;
    }

    /**
     * Retrieve the models that coudn't be imported. All of then
     * are filled with an ErrorBag in error attribute
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retrieve the models that have been imported
     *
     * @return array
     */
    public function getSuccess()
    {
        return $this->success;
    }
}
