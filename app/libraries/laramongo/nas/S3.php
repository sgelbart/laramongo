<?php namespace Laramongo\Nas;

class S3 {
    
    public $oyatelS3;

    public $bucket;

    public function __construct()
    {
        $aws_access_key = \Config::get('s3.aws_access_key','222');
        $aws_secret_key = \Config::get('s3.aws_secret_key','111');
        $endpoint =       \Config::get('s3.endpoint','s3.amazonaws.com');
        $this->bucket =   \Config::get('s3.bucket','111');

        $this->oyatelS3 = new \S3($aws_access_key, $aws_secret_key, false, $endpoint);
    }

    public function sendFile( $file )
    {
        $full_filename = app()->path.'/../public/'.$file;

        if(file_exists($full_filename))
        {            
            return $this->oyatelS3->putObject(
                $this->oyatelS3->inputFile($full_filename),
                $this->bucket,
                $file,
                \S3::ACL_PUBLIC_READ
            );
        }
        else
        {
            return false;
        }
    }
}
