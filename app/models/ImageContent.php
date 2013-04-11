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
        'image' => 'required',
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
}
