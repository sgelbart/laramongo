<?php

class ProcessImports{
    
    public function fire($job, $data)
    {        
        foreach( Import::all() as $i )
        {
            $i->process();
        }

        $job->delete();
    }

}
