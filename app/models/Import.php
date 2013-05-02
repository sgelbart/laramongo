<?php

use Illuminate\Support\MessageBag;
use Laramongo\ExcelIo\ExcelImporter;
use Laramongo\ExcelIo\ExcelVintageImporter;

class Import extends DelayedTask {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'delayedTasks';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'filename'     => 'required',
    );

    /**
     * Stores the ExcelIo instantiated
     * @var Laramongo\ExcelIo\ExcelIo
     */
    public $excelIo = null;

    /**
     * __construct defines the kind attribute to 'import'
     * in order do polymorphise later
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setAttribute('type','import');
    }

    /**
     * Process the batchFile
     */
    public function process()
    {
        if(! $this->isDone() )
        {
            // Import file
            if(! $this->excelIo)
                $this->excelIo = new ExcelImporter;

            $this->excelIo->importFile($this->filename);

            // Retreive results
            $this->success = $this->excelIo->getSuccess();
            $this->fail = $this->excelIo->getErrors();

            // Remove temporary file
            if (file_exists(app_path().$this->filename))
            {
                unlink(app_path().$this->filename);
            }

            $this->done = true;

            return $this->save();
        }
    }
}
