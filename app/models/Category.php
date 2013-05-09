<?php

use Illuminate\Support\MessageBag;
use Laramongo\SearchEngine\Searchable;

class Category extends BaseModel implements Traits\ToTreeInterface, Searchable {
    use Traits\HasImage, Traits\ToTree, Traits\ToSelect, Traits\ToPopover, Traits\Searchable;

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'categories';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required'
    );

    /**
     * Attributes that will be generated by FactoryMuff
     */
    public static $factory = array(
        'name' => 'string',
        'parents' => array(),
        'shortDesc' => 'text',
        'description' => 'text',
        'template' => 'base',
        'productTemplate' => 'base'
    );

    /**
     * These attributes will not be mass set
     */
    public $guarded = array(
        'image_file',
        '_id',
        '_method'
    );

    /**
     * Protected attribute containing the options of the tree
     *
     * @var array
     */
    static protected $treeOptions = array(
        'nodeView' => 'admin.categories._tree_node',
        'nodeName' => 'category'
    );

    protected $popoverView = 'admin.categories._popover';

    /**
     * Reference to parent
     */
    public function parents()
    {
        return $this->referencesMany('Category','parents');
    }

    /**
     * Embedded characteristics
     */
    public function characteristics()
    {
        return $this->embedsMany('Characteristic','characteristics');
    }

    /**
     * A full ancestors tree
     */
    public function ancestors()
    {
        return $this->embedsMany('Category','ancestors');
    }

    /**
     * Return all the childs. Use carefully.
     *
     */
    public function childs()
    {
        return Category::where(['parents'=>$this->_id]);
    }

    /**
     * Determines if a category is visible or not. This takes a decision
     * assembling the following facts:
     * - hidden is not any sort of 'true'
     * - category has an _id
     */
    public function isVisible()
    {
        return
            $this->hidden == false &&
            $this->_id != false;
    }

    /**
     * Simply set the hidden attribute to true
     */
    public function hide()
    {
        $this->hidden = true;
    }

    /**
     * Simply unset the hidden attribute
     */
    public function unhide()
    {
        unset($this->hidden);
    }

    /**
     * Save the model to the database if it's valid
     * Before saving, build ancestor tree
     *
     * @return bool
     */
    public function save( $force = false )
    {

        if( $this->isValid() )
        {
            $this->searchEngineIndex();
            $this->buildAncestors();
            return parent::save( $force );

            foreach ($this->childs() as $child)
            {
                $child->buildAncestors();
                $child->save( $force );
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Build ancestors tree within this category
     */
    public function buildAncestors()
    {
        unset($this->ancestors);
        if($this->parents())
        {
            $this->ancestors = $this->parents()->toArray();
        }
    }

    /**
     * Validate every product within this category. This may be used
     * in order to validate new characteristics that were included.
     */
    public function validateProducts()
    {
        foreach (Product::where(['category'=>(string)$this->_id]) as $product) {
            if(! $product->isValid())
            {
                $product->save(true);
            }
        }

        return true;
    }

    /**
     * Returns the ammount of products that this category have
     */
    public function productCount()
    {
        $productCount = Cache::remember("category_".$this->_id."_prod_count", 5, function()
            {
                return Product::where(['category'=>(string)$this->_id])->count();
            });

        return $productCount;
    }

    /**
     * Renders the menu
     *
     * @return string Html code of menu tree
     */
    public static function renderMenu()
    {
        $options = array(
            'nodeView' => 'layouts.website._menu_node',
            'nodeName' => 'category'
        );

        

        //return Cache::rememberForever('renderedMenu', function() use ($options){
            return static::renderTree( array(), $options );
        //});
    }

    /**
     * Return an array containing name, parent indexed
     * by _id. The purpose of this is to be used with
     * laravel's Form::select
     *
     * @return array
     */
    public static function toOptions( $query = array() )
    {
        $all = static::where( $query );
        $result = array();

        foreach ($all as $item) {

            $displayedName = $item->name;

            $ancestor = $item;
            while( isset($ancestor->ancestors()[0]) )
            {
                $ancestor = $ancestor->ancestors()[0];
                $displayedName =  $ancestor->name.' > '.$displayedName;
            }

            $result[(string)$item->_id] = $displayedName;
        }

        return $result;
    }

    /**
     * Returns an array of the facets if the category based in the
     * characteristics defined previously
     * 
     * @return array Facets
     */
    public function getFacets()
    {
        $facets = array();

        foreach($this->characteristics() as $charac)
        {
            $facets[$charac->name] = [
                'terms' => ['field'=>$charac->name]
            ];
        }
        
        return $facets;
    }
}
