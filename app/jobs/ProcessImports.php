<?php

class ProcessImports{
    
    public function fire($job, $data)
    {
        sleep(3);
        
        foreach( Import::all() as $i )
        {
            $i->process();
        }

        $job->delete();
    }

}
