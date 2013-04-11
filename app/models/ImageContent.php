<?php

class ImageContent extends Content {
    use Traits\HasImage;

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'contents';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
        'slug' => 'required',
        'kind' => 'required',
    );

    /**
     * Non-fillable attributes
     *
     * @var array
     */
    public $guarded = array(
        'image_file'
    );

    /**
     * Renders the image
     *
     * @param $width
     * @param $height
     * @return Html tag of the image
     */
    public function render( $width = null, $height = null )
    {
        $html = '<img alt="'.$this->name.'" src="'.$this->image.'">';

        return $html;
    }

    /**
     * Overwrites the isVisible method in order to only present objects
     * that have an Image attached.
     * Determines if a content is visible or not. This takes a decision
     * assembling the following facts:
     * - hidden is not any sort of 'true'
     * - imageContent has an _id
     * - imageContent has an attached image
     */
    public function isVisible()
    {
        return 
            $this->hidden == false &&
            $this->approved == true &&
            $this->_id != false &&
            $this->image;
    }
}
