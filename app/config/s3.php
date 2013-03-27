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
    | Access Key and Secret Key
    |--------------------------------------------------------------------------
    |
    | Your AWS Access keys
    |
    */

    'aws_access_key' => '???',

    'aws_secret_key' => '???',

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

    'bucket' => 'laramongo-assets',

);
