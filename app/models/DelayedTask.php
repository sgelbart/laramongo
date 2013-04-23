<?php

class DelayedTask extends BaseModel {

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'delayedTasks';

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

    public function save($force = false)
    {
        $result = parent::save($force);

        if(! $this->isDone())
        {
            Queue::push('ProcessDelayedTasks');
        }

        return $result;
    }
}
