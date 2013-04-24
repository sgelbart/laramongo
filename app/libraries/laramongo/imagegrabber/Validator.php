<?php namespace Laramongo\ImageGrabber;

class Validator
{

    protected $evaluation = false;
    protected $image_properties = '';
    protected $image = '';

    /**
     * This method returns if the image is a valid characteristics
     * @return boolean
     */
    public function validate($image, $class_object, $config=array())
    {
        $this->$image = $image;

        if ($class_object == 'Product') {
            validateForProduct($characteristics);

        } elseif ($class_object == 'Category') {
            validateForCategory($characteristics);
        }

        $this->$image_properties = getimagesize($image);

        $this->loggingFile($image, $evaluation);

        return $evaluation;
    }

    /**
     * Write at log the images that don`t pass at validation
     * @param  boolean $evaluation [description]
     * @param  string $image [description]
     */
    protected function loggingFile($image, $evaluation)
    {
        Log::warning('The image: ' . $image . ' don`t pass');
    }

    protected function validateForProduct($characteristics)
    {
        foreach ($characteristics['product']['max-size'] as $key => $value) {
            if (filesize($this->$image) > $value) {
                return false;
            }

            if ($image_properties[0] == $value) {
                return false;
            }

            if ($image_properties[1] == $value) {
                return false;
            }

            return true;
        }
    }
}
