<?php

trait TestHelper {

    /**
     * Clean database collection
     */
    protected function cleanCollection( $collection )
    {
        $database = Config::get('database.mongodb.default.database');

        $connector = new Zizaco\Mongolid\MongoDbConnector;
        $connection = $connector->getConnection();

        $connection->$database->$collection->drop();
    }
}
