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
        $style = '';
        if($width && $height)
        {
            $style .= ' style="';
            $style .= ($width) ? 'width: '.$width.'px;' : '';
            $style .= ($height) ? 'height: '.$height.'px;' : '';
            $style .= '" ';
        }

        $productTags = $this->renderTags();

        $html = '<span class="tagged-image">'.
            '<img class="tagged-image" alt="'.$this->name.'" src="'.$this->imageUrl().'" '.$style.'>'.
            $productTags.
        '</span>';

        return $html;
    }

    /**
     * Return the html code of the products that ate tagged in the
     * image
     * @return string Html code of the rendered tags
     */
    private function renderTags()
    {
        $rendered = '';

        if(! $this->tagged)
            return '';

        foreach ($this->products() as $product) {
            foreach ($this->tagged as $tag) {
                if($tag['_id'] == $product->_id)
                {
                    $rendered .=
                    '<a href="'.URL::action('ProductsController@show', ['id'=>$product->_id]).'">'.
                    '<span class="tag" title="'.$product->name.'" style="left:'.$tag['x'].'%; top:'.$tag['y'].'%"></span>'.
                    '</a>';
                }
            }
        }

        return $rendered;
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

    /**
     * Tags an product that is already related with this content
     * into the picture to used with a facebook like image tagging
     * 
     * @param  mixed $product Object or id of the product (This product should already be referenced in the products attribute)
     * @param  int   $x       x Position of the tag in the image
     * @param  int   $y       y Position of the tag in the image
     * @return bool           Returns true if the product was created successfuly.
     */
    public function tagProduct( $product, $x, $y )
    {
        if($product instanceOf Product)
        {
            $product_id = $product->_id;
        }
        else
        {
            $product_id = $product;
        }

        // If the product is previously related with the content.
        if(in_array($product_id, (array)$this->products))
        {
            $this->embedToTagged(['_id'=>$product_id, 'x'=>$x, 'y'=>$y]);
            return true; // Product tagged successfully
        }
        else
        {
            return false; // The product is not related. Call 'attachToProducts' before.
        }
    }

    /**
     * Removes a tagged product from the tagged attribute
     * @param  mixed  $product Product object or id
     * @return null
     */
    public function untagProduct($product)
    {
        $this->unembed('tagged',$product);
    }

    /**
     * Overwrites the detach method in order to remove any tagged
     * Product from the tagged attribute
     * 
     * @param string $field
     * @param mixed $obj _id, document or model instance
     * @return void
     */
    public function detach($field, $obj)
    {
        parent::detach($field, $obj);

        if($field == 'products')
        {       
            $this->untagProduct($obj);
        }
    }
}
