<?php namespace Laramongo\StoresProductsIntegration;

use Illuminate\Support\MessageBag;

/**
 * This class represents an Price importing process
 */
class StoreUpdate extends \DelayedTask {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'temp_delayedTasks';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'filename'     => 'required',
    );

    /**
     * Stores the instantiated CsvParser
     * @var Laramongo\StoresProductsIntegration\CsvParser
     */
    public $parser = null;

    /**
     * __construct defines the kind attribute to 'storeUpdate'
     * in order do polymorphise later
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setAttribute('type','storeUpdate');

        $this->parser = new CsvParser;
    }

    /**
     * Process the prices file
     */
    public function process()
    {
        if(! $this->isDone() )
        {
            $path = substr($this->filename, 0, -4);

            $this->extractGz( app_path().$this->filename, app_path().'/'.$path );
            $extractedFilename = str_replace('.gz', '', $this->filename);

            \Log::info("Processing Prices file: $this->filename");

            $this->parser->parseFile($extractedFilename);
            
            $this->done = true;

            return $this->save();
        }
    }

    /**
     * Extracts a gz file
     * 
     * @param string sourcefile Which files we need to extract  
     * @param sstring desfile name to create the new file  
     * @param int buffer_size increase performance  
     */    
    protected function extractGz($sourcefile, $desfile, $buffer_size = 4096)    
    {    
        //Here replace the .gz to nul now the file name repalce to xxx.xml only
        $desfile = str_replace('.gz', '', $sourcefile);

        //Open the file in binary mode because we change the file .xml.gz to .xml
        $file = gzopen($sourcefile,'rb');

        //Open the output file
        $out_file = fopen($desfile, 'wb');

        //Repeating until the end of the input file
        while(!gzeof($file))
        {
            fwrite($out_file, gzread($file, $buffer_size));
        }

        fclose($out_file);

        return $desfile;
    }
}
