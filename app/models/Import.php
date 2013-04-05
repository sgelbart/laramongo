<?php

use Illuminate\Support\MessageBag;
use Zizaco\CsvToMongo\Importer;

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
        'category'     => 'required',
    );

    /**
     * Reference to category
     */
    public function category()
    {
        return $this->referencesOne('Category','category');
    }

    /**
     * Process the batchFile
     */
    public function process()
    {
        if(! $this->isDone() )
        {
            // Import file
            $importer = new Importer($this->filename,'Product');
            $importer->import( $this->category, $this->isConjugated );

            // Retreive results
            $this->success = $importer->getSuccess();
            $this->fail = $importer->getErrors();

            // Remove temporary file
            unlink($this->filename);

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
