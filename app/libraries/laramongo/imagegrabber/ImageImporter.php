<?php namespace Laramongo\ImageGrabber;

class ImageImporter
{
    public function import($url)
    {
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

        $ch = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );

        if (curl_getinfo($ch)['http_code'] == "200") {
            return $content;
        }

        curl_close( $ch );
        return false;
    }
}
