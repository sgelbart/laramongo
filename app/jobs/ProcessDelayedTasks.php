<?php

class ProcessDelayedTasks{
    
    public function fire($job, $data)
    {        
        $i = DelayedTask::first([ // Get next DelayedTask that is...
            'done'=>null,       // not done and...
            'tries'=>['$lt'=>5] // not failed 5 times.
        ]);

        if($i)
        {
            $i->incTries(); // Mark the ammount of tries

            $i->process();
        }

        $job->delete();
    }

}
