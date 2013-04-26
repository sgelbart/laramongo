<?php

class DelayedTask extends BaseModel {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'delayedTasks';

    /**
     * Process this tasks
     * 
     * @return null
     */
    public function process()
    {
        // Marks task as complete
        $this->done = true;

        $this->save();
    }

    /**
     * Returns true if the import is complete
     */
    public function isDone()
    {
        return $this->done == true;
    }

    /**
     * Overwrites the save method in order to put in the
     * queue the new DelayedTasks
     * 
     * @param  boolean $force
     * @return boolean success
     */
    public function save($force = false)
    {
        $result = parent::save($force);

        if(! $this->isDone())
        {
            Queue::push('ProcessDelayedTasks');
        }

        return $result;
    }

    /**
     * Polymorph into specific delayedtasks based on kind
     * 
     * @param DelayedTask $instance Object to be polymorphed
     * @return mixed $instance
     */
    public function polymorph( $instance )
    {
        if( $instance->kind == 'import' )
        {
            $import = new Import;

            $import->parseDocument( $instance->attributes );
            return $import;
        }
        elseif( $instance->kind == 'massImport' )
        {
            $import = new MassImport;

            $import->parseDocument( $instance->attributes );
            return $import;
        }
        else
        {
            return $instance;
        }
    }
}
