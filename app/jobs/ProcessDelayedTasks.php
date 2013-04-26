<?php

class ProcessDelayedTasks{
    
    public function fire($job, $data)
    {        
        $i = DelayedTask::first(['done'=>null]);

        if($i)
        {
            $i->process();
        }

        $job->delete();
    }

}
