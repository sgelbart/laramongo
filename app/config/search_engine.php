<?php

/**
 * Configuration about search engine like Exalead, Elastic Search, etc.
 */

return array(
    'enabled' => true,
    'application_name' => 'dev',
    'engine' => 'ElasticSearchEngine',

    'settings' => array(
        'elastic_search' =>  array (
            'connection_url' => isset($_SERVER['PARAM5']) ? $_SERVER['PARAM5'] : 'http://127.0.0.1:9200'
        ),

        'exalead' => array ()
    )
);
