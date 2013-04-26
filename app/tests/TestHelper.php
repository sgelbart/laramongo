<?php

trait TestHelper {

    /**
     * Clean database collection
     */
    protected function cleanCollection( $collection )
    {
        $db = LMongo::connection();
        $db->$collection->drop();
    } 
}
