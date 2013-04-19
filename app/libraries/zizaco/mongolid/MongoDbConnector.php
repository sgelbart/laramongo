<?php namespace Zizaco\Mongolid;

use MongoClient;

class MongoDbConnector{

    /**
     * The connection name for the model.
     *
     * @var MongoDB
     */
    public static $shared_connection;

    /**
     * Returns the connection. If non existent then create it
     *
     * @var MongoDB
     */
    public function getConnection()
    {
        // If exists in $shared_connection, use it
        if( MongoDbConnector::$shared_connection )
        {
            $connection = MongoDbConnector::$shared_connection;
        }
        // Else, connect place connection in $shared_connection
        else
        {
            $connectionString = 'mongodb://'.
                \Config::get('lmongo::connections.default.host').
                ':'.
                \Config::get('lmongo::connections.default.port').
                '/'.
                \Config::get('lmongo::connections.default.database');

            try{
                $connection = new MongoClient($connectionString);
            }
            catch(\MongoConnectionException $e)
            {
                sleep(1);
                $connection = new MongoClient($connectionString);
            }

            MongoDbConnector::$shared_connection = $connection;
        }

        return $connection;
    }

}
