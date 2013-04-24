<?php namespace Laramongo\ImageGrabber;

use Config;

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
     * 
     * @param   $obj Product,Category
     */
    public function grab($obj)
    {
        $this->object = $obj;

        $this->origin_url = Config::get('image_grabber.origin_url');
        $this->destination_url = Config::get('image_grabber.destination_url');

        $sizes = Config::get('image_grabber.image.sizes');
        $angles = Config::get('image_grabber.image.angles');

        if ($this->object instanceof \Product)
        {
            $this->retrieveProductImages($sizes, $angles);
        }
        elseif($this->object instanceof \Category)
        {
            $this->retrieveCategoryImages();
        }
        else
        {
            trigger_error(
                "ImageGrabber::grab method should receive an ".
                "instance of Product or Category"
            );
        }
    }

    /**
     * Runs accross some angles an size of images of an Product which is
     * in the $this->object attribute.
     * 
     * @param  array $sizes
     * @param  array $angles
     * @return  null
     */
    protected function retrieveProductImages($sizes, $angles)
    {
        // For each image size (ex: 100, 150, 300)
        foreach ($sizes as $size) {

            // For each angle (one image may have different angles for each size)
            foreach ($angles as $angle) {

                // replacing with angle and sizes
                $origin = $this->prepareUrl($this->origin_url, $angle, $size);
                $destination = $this->prepareUrl( $this->destination_url, $angle, $size);

                // getting result of get image
                $result = $this->getImage($origin, $destination);

                // If result is false (image was not found), break the angle loop
                // in order to check for the next size.
                if (! $result) {
                    break;
                }
            }
        }
    }

    /**
     * Retrieve the image of the Category contained in $this->object
     * 
     * @return  null
     */
    protected function retrieveCategoryImages()
    {
        $origin = $this->prepareUrl($this->origin_url);
        $destination = $this->prepareUrl( $this->destination_url );

        // getting result of get image
        $this->getImage($origin, $destination);
    }

    /**
     * Actually creates the image file (i.e: write the contents retrieved from
     * curl into a file)
     * 
     * @param  string $origin_url      origin url image
     * @param  string $destination_url destination url
     * @return boolean Success
     */
    protected function getImage($origin_url, $destination_url)
    {
        $tmp = $this->getUrl($origin_url);

        if ($tmp) {
            try{
                if(! file_exists(dirname($destination_url))) {
                    mkdir(dirname($destination_url));
                }

                $fp = fopen($destination_url, 'wb');
                fwrite($fp, $tmp);
                fclose($fp);
            }
            catch(\Exception $e)
            {
                Log::warning(
                    "The ImageGrabber could not create the file $destination_url, ".
                    "please check the directory permissions or if it exists"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Get the content for url. I.E: Curl. Returns false if the
     * url cannot be reached.
     * 
     * @param  $url
     * @return string Binary content of the file
     */
    protected function getUrl($url)
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
     * 
     * @return the url to get images
     */
    protected function prepareUrl($url = array(), $angle = '1', $size = '150')
    {
        $stringToReplace = '';

        if ($this->object instanceof \Product) {
            $stringToReplace = $url['product'];

            $tags = [
                '{angle}','{size}','{collection}','{lm}',
            ];

            $values = [
                $angle, $size,
                $this->object->getCollectionName(),
                $this->object->_id,
            ];

            $stringToReplace = str_replace($tags, $values, $stringToReplace);
        } else {
            $stringToReplace = $url['chave_entrada'];
            $stringToReplace = str_replace('{lm}', $this->object->_id, $stringToReplace);
        }

        return $stringToReplace;
    }

    /**
     * Returns and records if the image is valid in size and weight
     *
     * @param  $imagePath Path to the image file
     * @param  $width Ideal width of the image
     * @param  $height Ideal height of the image, can be ommited to square images
     * @return  boolean Is valid?
     */
    protected function isValid($imagePath, $width, $height = null)
    {
        if(! $height)
            $height = $width;

        // Some estimations about the image weight (may be adjusted later)
        if( $width == 550 && height == 360)
            $weight = 150; // KB
        elseif( $width <= 280 )
            $weight = 20; // KB
        elseif( $width > 280 && $width <= 580 )
            $weight = 60; // KB
        elseif( $width > 580 )
            $weight = 150; // KB

        $params = [
            'width' => $width,
            'height' => $height,
            'weight' => $weight,
        ];

        $validator = new Validator;
        return $validator->validate( $imagePath, $params );
    }
}
