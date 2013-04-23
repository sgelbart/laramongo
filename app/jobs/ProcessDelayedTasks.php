<?php

class ProcessDelayedTasks{
    
    public function fire($job, $data)
    {        
        foreach( DelayedTask::all() as $i )
        {
            $i->process();
        }

        $job->delete();
    }

}
