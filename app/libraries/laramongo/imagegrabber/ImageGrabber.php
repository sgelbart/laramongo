<?php namespace Laramongo\ImageGrabber;

class ImageGrabber {

    protected $object;

    public function grab($obj)
    {
        $this->object = $obj;

        $origin_url = \Config::get('image_grabber.origin_url');
        $destination_url = \Config::get('image_grabber.destination_url');

        $sizes = \Config::get('image_grabber.image.sizes');
        $angles = \Config::get('image_grabber.image.angles');

        foreach ($sizes as $size) {
            foreach ($angles as $angle) {
                // replacing with angle and sizes

                $origin_url = $this->prepareUrlOrigin($origin_url, $angle, $size);
                $destination_url = $this->prepareUrlDestination( app_path() . '/../' .  $destination_url, $angle, $size);

                // getting image

                echo "initial setup:\n";

                print_r($destination_url . "\n");
                print_r($origin_url . "\n");

                echo "final prepare \n";

                $this->get_image( $origin_url, $destination_url );
            }
        }
    }


    protected function get_image($origin_url, $destination_url)
    {
        $tmp = $this->get_url($origin_url);

        if ($tmp) {
            $fp = fopen($destination_url, 'wb');
            fwrite($fp, $tmp);
            fclose($fp);
        }
    }

    protected function get_url($url)
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

        $ch      = curl_init($url);
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );

        if (curl_getinfo($ch)['http_code'] != "404") {
            return $content;
        } else {
            return false;
        }

        curl_close( $ch );
    }

    protected function prepareUrlOrigin($url, $angle, $size)
    {
        $string_replaced = '';
        $string_to_replace = '';

        if ($url['product'] && $this->object instanceof \Product) {
            $string_to_replace = $url['product'];
        }
        else {
            $string_to_replace = $url['chave_entrada'];
        }

        if ($this->object instanceof \Product) {
            $string_replaced = str_replace('{angle}', $angle, $string_to_replace);
            $string_replaced = str_replace('{size}', $size, $string_replaced);
            $string_replaced = str_replace('{lm}', $this->object->_id, $string_replaced);

        } else {
            $string_replaced = str_replace('{lm}', $this->object->_id, $string_to_replace);
        }

        return $string_replaced;
    }

    protected function prepareUrlDestination($url, $angle, $size)
    {
        $string_to_replace = $url;

        $string_to_replace = str_replace('{lm}', $this->object->_id, $string_to_replace);
        $string_to_replace = str_replace('{collection}', $this->object->getCollectioName(), $string_to_replace);
        $string_to_replace = str_replace('{angle}', $angle, $string_to_replace);
        $string_to_replace = str_replace('{size}', $size, $string_to_replace);

        return $string_to_replace;
    }
}
