<?php namespace Zizaco\CsvToMongo;

use Keboola\Csv\CsvFile;

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
        '_id','name','description','small_description','category'
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
    public function import( $category )
    {
        $headers = array();

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

                foreach ($attributes as $key => $value) {
                    if(! in_array($key, $this->non_characteristic_keys))
                    {
                        $attributes['details'][$key] = $value;
                        unset($attributes[$key]);
                    }
                }

                if( $instance->parseDocument(
                    $attributes
                ))
                {
                    // Set the leaf category where that product belongs
                    $instance->category = $category;

                    if( $instance->save(true) )
                    {
                        $this->success[] = $instance;
                    }
                    else
                    {
                        $this->errors[] = $instance;
                    }
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
