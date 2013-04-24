<?php namespace Laramongo\ImageGrabber;

class Validator
{

    /**
     * Contains the last validation error
     * 
     * @var string
     */
    protected $lastInvalid = '';

    /**
     * This method returns if the image is a valid characteristics
     * @return boolean
     */
    public function validate($imagePath, $params)
    {
        // Return false if file doesn't exists
        if(! file_exists($imagePath))
            return false;

        // Get the image size and check if it meets the parameters
        $size = getimagesize($imagePath);

        if( isset($params['width']) && $size[0] != $params['width'] )
        {
            $this->lastInvalid .= "Width $size[0] is out of the permited size (".$params['width'].")";
            return false;
        }

        if( isset($params['height']) && $size[1] != $params['height'] )
        {
            $this->lastInvalid .= "Height $size[1] is out of the permited size (".$params['height'].")";
            return false;
        }

        // Get the filesize and check if it meets the parameter
        if( isset($params['weight']) && filesize($imagePath) > $params['weight']*1000 )
        {
            $this->lastInvalid .= "Weight ".filesize($imagePath)." is out of the permited size (".($params['weight']*1000).")";
            return false;
        }

        $this->lastInvalid = '';
        return true;
    }

    public function getLastInvalid()
    {
        if($this->lastInvalid)
            return $this->lastInvalid;

        return false;
    }
}
