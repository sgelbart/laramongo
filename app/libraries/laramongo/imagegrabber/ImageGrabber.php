<?php namespace Laramongo\ImageGrabber;

class ImageGrabber {

    /**
     * Store the object passed at grab method.
     * @var Product, Category
     */
    protected $object;

    protected $origin_url;

    protected $destination_url;

    public $imageImporter;

    /**
     * Grab the images from website
     * @param   $obj Product,Category
     */
    public function grab($obj)
    {

        $this->object = $obj;

        $this->origin_url = \Config::get('image_grabber.origin_url');
        $this->destination_url = \Config::get('image_grabber.destination_url');

        $sizes = \Config::get('image_grabber.image.sizes');
        $angles = \Config::get('image_grabber.image.angles');

        $this->retrieve_images($sizes, $angles);
    }



    /**
     * Runs accross some angles an size of images if the object is a instance of
     * Product else just get the image
     * @param  array $sizes
     * @param  array $angles
     */
    protected function retrieve_images($sizes, $angles)
    {
        if ($this->object instanceof \Product) {
            foreach ($sizes as $size) {
                foreach ($angles as $angle) {
                    // replacing with angle and sizes
                    $origin = $this->prepareUrl($this->origin_url, $angle, $size);
                    $destination = $this->prepareUrl( $this->destination_url, $angle, $size);

                    // getting result of get image
                    $result = $this->get_image($origin, $destination);

                    if (! $result) {
                        break;
                    }
                }
            }
        } else {
            $origin = $this->prepareUrl($this->origin_url);
            $destination = $this->prepareUrl( $this->destination_url );

            // getting result of get image
            $result = $this->get_image($origin, $destination);
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
        $importer = \App::make('RemoteImporter');
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
    protected function prepareUrl($url=array(), $angle=null, $size=null)
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
}
