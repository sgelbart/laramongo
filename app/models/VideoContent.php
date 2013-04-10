<?php

class VideoContent extends Content {

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
        'youTubeId' => 'required',
    );

    /**
     * Render the video as an embedded youTube video
     *
     * @param $width
     * @param $height
     * @return Html of the embedded video
     */
    public function render( $width = 560, $height = 315 )
    {
        $html = '<iframe width="'.$width.'" height="'.$height.'" '.
                'src="http://www.youtube.com/embed/'.$this->youTubeId.'?rel=0" '.
                'frameborder="0" allowfullscreen></iframe>';

        return $html;
    }

    /**
     * Overwrites the setAttribute method in order to
     * parse the video link for the youtube video id
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if($key == 'youTubeId')
        {
            $pattern = '/v=(\w+)&?/';
            if(preg_match($pattern, $value, $matches))
            {
                $value = $matches[1];
            }
        }

        return parent::setAttribute($key, $value);
    }

}
