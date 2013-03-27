<?php namespace Laramongo\Nas;

use Config;

/**
 * This class has the responsability to reach the assets
 * locally and remotelly, depending on the environment.
 *
 */
class Asset {

    protected static $instance;

    public static function url( $url )
    {
        return static::instance()->urlTo($url);
    }

    public function urlTo( $url )
    {
        if(Config::get('s3.enabled', false))
        {
            $defaultRemoteUrl = 'https://'.
                Config::get('s3.endpoint').'/'.
                Config::get('s3.bucket').'/';

            return Config::get('s3.base_remote_url',$defaultRemoteUrl).$url;    
        }
        else
        {
            return $url;
        }
        
    }

    public static function instance()
    {
        if(! static::$instance)
        {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
