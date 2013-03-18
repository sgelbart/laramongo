<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	*/

	'default' => 'default',

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	*/

	'connections' => array(

		'default' => array(
            'host'     => isset($_SERVER['PARAM1']) ? $_SERVER['PARAM1'] : '127.0.0.1',
            'port'     => isset($_SERVER['PARAM2']) ? $_SERVER['PARAM2'] : 27017,
            'database' => 'laramongo',
        ),
	),
);
