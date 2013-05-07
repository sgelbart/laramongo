<?php

/**
 * Configuration about search engine like Exalead, Elastic Search, etc.
 */

return array(
    'application_name' => 'dev',
    'engine' => 'ElasticSearch',

    'settings' => array(
        'elastic_search' =>  array (
            'connection_url' => 'http://127.0.0.1:9200'
        ),

        'exalead' => array ()
    )
);
