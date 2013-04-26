<?php namespace Laramongo\ImageGrabber;

class Validator
{

    /**
     * Contains the last validation error
     *
     * @var string
     */
    protected $lastInvalid = '';
    protected $imagePath = '';

    /**
     * This method returns if the image is a valid characteristics
     * @return boolean
     */
    public function validate($imagePath, $params)
    {
        $this->imagePath = $imagePath;

        // Return false if file doesn't exists
        if(! file_exists($imagePath))
        {
            $this->logInvalid("Image not found!");
            return false;
        }
            

        // Get the image size and check if it meets the parameters
        $size = getimagesize($imagePath);

        if( isset($params['width']) && $size[0] != $params['width'] )
        {
            $this->logInvalid("Width $size[0] is out of the permited size (".$params['width'].")");
            return false;
        }

        if( isset($params['height']) && $size[1] != $params['height'] )
        {
            $this->logInvalid("Height $size[1] is out of the permited size (".$params['height'].")");
            return false;
        }

        // Get the filesize and check if it meets the parameter
        if( isset($params['weight']) && filesize($imagePath) > $params['weight']*1000 )
        {
            $this->logInvalid("Weight ".filesize($imagePath)." is out of the permited size (".($params['weight']*1000).")");
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

    /**
     * Record thr information at valid image
     *
     * @param  string $message description of why is invalid
     * @return null
     */
    protected function logInvalid($message)
    {
        $this->lastInvalid .= $message;

        \Log::warning($this->imagePath . ": ". $message);
    }
}
