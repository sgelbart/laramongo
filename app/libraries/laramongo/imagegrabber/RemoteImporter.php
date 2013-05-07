<?php namespace Laramongo\ImageGrabber;

use Log;

/**
 * This class has the responsability to get through HTTP resquest the content
 * passed as a parameter.
 */
class RemoteImporter
{
    /**
     * Returns the content if success request or return false.
     * @param  string $url to get content
     */
    public function import($url)
    {
        Log::info('cURL: '.$url);

        // In order to speed test, don`t make real request at
        // testing environment
        if (app()->environment() == 'testing')
            return false;

        // Creating options for cURL.
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 5,      // timeout on connect
            CURLOPT_TIMEOUT        => 5,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,
        );

        if( isset($_SERVER['http_proxy']) )
        {
            $proxy = $_SERVER['http_proxy'];

            $address = explode(':',$proxy,10);
            $address = $address[0].':'.$address[1];
            $port = intval(array_get(explode(':',$proxy),2));

            $options[CURLOPT_PROXY]          = $address;
            $options[CURLOPT_PROXYPORT]      = $port;
            $options[CURLOPT_PROXYUSERPWD]   = 'central\lalves:leroymerlin1';

            Log::info('cURL using proxy: '.$proxy);
        }

        // initializing cURL
        $ch = curl_init( $url );

        // setting properties
        curl_setopt_array( $ch, $options );

        // Getting content
        $content = curl_exec( $ch );

        // getting HTTP_CODE
        if (curl_getinfo($ch)['http_code'] == "200") {
            return $content;
        }

        // close session
        curl_close( $ch );

        return false;
    }
}
