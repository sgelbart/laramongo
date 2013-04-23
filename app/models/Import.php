<?php

use Illuminate\Support\MessageBag;
use Laramongo\ExcelIo\ExcelIo;

class Import extends BaseModel {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'imports';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'filename'     => 'required',
    );

    /**
     * Process the batchFile
     */
    public function process()
    {
        if(! $this->isDone() )
        {
            // Import file
            $io = new ExcelIo;
            $io->importFile($this->filename);

            // Retreive results
            $this->success = array();
            $this->fail = array();

            // Remove temporary file
            unlink(app_path().$this->filename);

            $this->done = true;

            return $this->save();
        }
    }

    /**
     * Returns true if the import is complete
     */
    public function isDone()
    {
        return $this->done == true;
    }

    public function save($force = false)
    {
        $result = parent::save($force);

        Queue::push('ProcessImports');

        return $result;
    }

}
