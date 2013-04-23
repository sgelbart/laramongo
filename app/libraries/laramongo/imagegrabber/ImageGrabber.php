<?php namespace Laramongo\ImageGrabber;

class ImageGrabber {

    /**
     * Store the object passed at grab method.
     * @var Product, Category
     */
    protected $object;
    public $imageImporter;

    /**
     * Grab the images from website
     * @param   $obj Product,Category
     */
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
                $origin = $this->prepareUrlOrigin($origin_url, $angle, $size);
                $destination = $this->prepareUrlDestination( app_path() . '/../' .  $destination_url, $angle, $size);

                $result = $this->get_image($origin, $destination);

                if (! $result) {
                    break;
                }
            }
        }
    }


    /**
     * Create image file
     * @param  [type] $origin_url      origin url image
     * @param  [type] $destination_url destination url
     * @return [type]                  the response
     */
    protected function get_image($origin_url, $destination_url)
    {
        $tmp = $this->get_url($origin_url);

        if ($tmp) {
            $fp = fopen($destination_url, 'wb');
            fwrite($fp, $tmp);
            fclose($fp);
        }

        return $tmp;
    }

    /**
     * Get the content for url
     * @param  $url
     * @return [type]      [description]
     */
    protected function get_url($url)
    {
        $importer = \App::make('ImageImporter');
        $response = $importer->import($url);

        if ($response) {
            return $response;
        }

        return false;
    }

    /**
     * Prepare the url Origin, replacing the parameters
     * @return the url to get images
     */
    protected function prepareUrlOrigin($url, $angle, $size)
    {
        $string_to_replace = '';

        if ($this->object instanceof \Product) {
            $string_to_replace = $url['product'];

            $string_to_replace = str_replace('{angle}', $angle, $string_to_replace);
            $string_to_replace = str_replace('{size}', $size, $string_to_replace);
            $string_to_replace = str_replace('{lm}', $this->object->_id, $string_to_replace);

        } else {
            $string_to_replace = $url['chave_entrada'];
            $string_to_replace = str_replace('{lm}', $this->object->_id, $string_to_replace);
        }

        return $string_to_replace;
    }

    /**
     * Prepare the url Destination, replacing the parameters
     * @return the url to put images
     */
    protected function prepareUrlDestination($url, $angle, $size)
    {
        $string_to_replace = $url;

        $string_to_replace = str_replace('{lm}', $this->object->_id, $string_to_replace);
        $string_to_replace = str_replace('{collection}', $this->object->getCollectionName(), $string_to_replace);
        $string_to_replace = str_replace('{angle}', $angle, $string_to_replace);
        $string_to_replace = str_replace('{size}', $size, $string_to_replace);

        return $string_to_replace;
    }
}
