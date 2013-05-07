<?php namespace Laramongo\HealthCheck;

use Session, MongoClient, Config;
use Laramongo\Nas\S3;

class HealthCheck
{
    public function checkSession()
    {
        Session::put('test', 'result');
        return Session::get('test') == 'result';
    }

    public function checkDatabase()
    {
        $connection = new MongoClient(
            'mongodb://'.
            Config::get('lmongo::connections.default.host').
            ':'.
            Config::get('lmongo::connections.default.port').
            '/'.
            Config::get('database.mongodb.default.database').
            '?readPreference=primary'
        );

        $database = Config::get('database.mongodb.default.database');

        $db = $connection->$database;

        $test_array = ['test'=>'result'];
        $db->test->save(['test'=>'result']);

        $result = array_get($test_array,'test', 1) == array_get($db->test->findOne(),'test',0);

        $db->test->drop();
        return $result;
    }

    public function checkNas()
    {
        if(Config::get('s3.enabled', false))
        {
            $s3 = new S3;
            return count((array)$s3->listBuckets()) > 0;
        }
        else
        {
            return false;
        }
    }

    public function renderResults()
    {
        $html = 
            "
            <html>
            <head>
                <style type='text/css'>
                    body{
                        background-color: #000;
                        color: #fff;
                        font-family: sans-serif;
                        width: 400px;
                        margin: auto;
                    }
                    .success{color: #4F4;}
                    .error{color: #F44;}
                </style>
            </head>
            </body>
                <h1>Health Check</h1>
                <div>
                    <h2>Host name</h2>
                    <p>
                        Value: 
                        <span class='success'>".gethostname()."</span>
                    </p>
                </div>
                <div>
                    <h2>Session</h2>
                    <p>
                        Status: 
                        ".(($this->checkSession()) ? "<span class='success'>Working</span>" : "<span class='error'>With problems</span>")."
                    </p>
                </div>
                <div>
                    <h2>Database</h2>
                    <p>
                        Status: 
                        ".(($this->checkDatabase()) ? "<span class='success'>Working</span>" : "<span class='error'>With problems</span>")."
                    </p>
                </div>
                <div>
                    <h2>NAS</h2>
                    <p>
                        Status: 
                        ".(($this->checkNas()) ? "<span class='success'>Working</span>" : "<span class='error'>With problems</span>")."
                    </p>
                </div>
            </html></body>
            ";

        return $html;
    }
}
