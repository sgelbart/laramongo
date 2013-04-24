<?php namespace Laramongo\ImageGrabber;

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
            CURLOPT_PROXY          => \Config::get('image_grabber.proxy'),
            CURLOPT_PROXYPORT      => \Config::get('image_grabber.proxy_port'),
            CURLOPT_PROXYUSERPWD   => \Config::get('image_brabber.user')
        );

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
