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
        $fullFilename = \Config::get('s3.base_local_path').$file;

        if(file_exists($fullFilename))
        {            
            return $this->oyatelS3->putObject(
                $this->oyatelS3->inputFile($fullFilename),
                $this->bucket,
                $file,
                \S3::ACL_PUBLIC_READ
            );
        }
        
        return false;
    }

    public function send( $path )
    {
        // If '/' is the last character, remove it
        if(substr($path,-1) == '/')
            $path = substr($path,0,-1);

        // Get full path
        $fullPath = \Config::get('s3.base_local_path').$path;

        if(file_exists($fullPath))
        {
            // If is a directory then send content recursivelly
            if(is_dir($fullPath))
            {
                foreach( scandir($fullPath) as $file )
                {
                    if($file != '.' && $file != '..')
                    {
                        $this->send($path.'/'.$file);
                    }
                }
            }
            else // If not, then send file
            {
                $this->sendFile( $path );
            }

            // Done
            return true;
        }

        return false;
    }
}
