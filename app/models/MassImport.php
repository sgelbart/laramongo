<?php

use Illuminate\Support\MessageBag;
use VIPSoft\Unzip\Unzip;

class MassImport extends DelayedTask {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'delayedTasks';

    /**
     * VIPSoft Unzip
     *
     * @var VIPSoft\Unzip\Unzip
     */
    protected $unzipper;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'filename'     => 'required',
    );

    /**
     * __construct defines the kind attribute to 'import'
     * in order do polymorphise later
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setAttribute('kind','massImport');

        $this->unzipper  = new Unzip();
    }

    /**
     * Process the batchFile
     */
    public function process()
    {
        if(! $this->isDone() )
        {
            $path = substr($this->filename, 0, -4);

            echo app_path().$path."\n";
            if ( ! is_dir(app_path().$path) )
                mkdir(app_path().$path, 0777, true);

            $filenames = $this->unzipper->extract( app_path().$this->filename, app_path().$path );

            foreach ($filenames as $filename) {
                if(substr($filename,-4) == 'xlsx')
                {
                    Log::info("MassImport adding to queue:\n $filename");
                    echo("MassImport adding to queue:\n $filename \n");
                    // echo $path.'/'.$filename."\n";

                    // Creates the import object
                    $import = new Import;
                    $import->filename = $path.'/'.$filename;
                    $result = $import->save();
                }
            }

            $this->done = $result;

            return $this->save();
        }
    }
}
