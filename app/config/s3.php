<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Enable S3
    |--------------------------------------------------------------------------
    |
    | If you enable S3 than every asset url will point to the relative S3 url
    |
    */

    'enable' => true,

    /*
    |--------------------------------------------------------------------------
    | Base Path
    |--------------------------------------------------------------------------
    |
    | The base path of where the files will be sent do S3. I.E:
    | By setting this as the public path, if you have the 'public/img/a.jpg'
    | file, you should call S3->send('img/a.jpg');
    | 
    | This path will be used when referencing assets, i.e: Asset::url('img/a.jpg')
    | will point to the local /public/img/a.jpg if S3 is not enabled.
    |
    */
    'base_local_path' => app_path().'/../public/',

    /*
    |--------------------------------------------------------------------------
    | Access Key and Secret Key
    |--------------------------------------------------------------------------
    |
    | Your AWS Access keys
    |
    */

    'aws_access_key' => isset($_SERVER['AWS_ACCESS_KEY_ID']) ? $_SERVER['AWS_ACCESS_KEY_ID'] : '???',

    'aws_secret_key' => isset($_SERVER['AWS_SECRET_KEY']) ? $_SERVER['AWS_SECRET_KEY'] : '???',

    /*
    |--------------------------------------------------------------------------
    | Endpoint
    |--------------------------------------------------------------------------
    |
    | The S3 endpoint
    | You should change this depending on where your bucket is located, for
    | example: 's3-sa-east-1.amazonaws.com', 's3-ap-southeast-1.amazonaws.com', 
    | 's3-eu-west-1.amazonaws.com', etc.
    |
    */

    'endpoint' => 's3-sa-east-1.amazonaws.com',

    /*
    |--------------------------------------------------------------------------
    | Bucket name
    |--------------------------------------------------------------------------
    |
    | Name of the S3 bucket that will be used
    |
    */

    'bucket' => 'laramongo-develop',

);
