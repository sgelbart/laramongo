<?php

class ProductRepository
{

    public $perPage = 6;

    /**
     * Should return a cursor of Products. If there is
     * a term of the search, bring only product with that
     * name. If a kind is specified, brings only product
     * of that type
     *
     * @param $terms String to search in title or lm of product
     * @param $deactivated Display deactivated products
     * @return OdmCursor The results
     */
    public function search( $terms = null, $deactivated = null )
    {
        if(! $terms)
        {
            $query = array();
        }
        else
        {
            $query = [ '$or'=> [
                ['name'=> new \MongoRegex('/^'.$terms.'/i')],
                ['lm'=> new \MongoRegex('/^'.$terms.'/i')]
            ]];            
        }

        if($deactivated != 'true')
        {
            $query = array_merge($query,['deactivated'=>null]);
        }

        $products = Product::where($query);

        return $products;
    }

    /**
     * Return the ammount of pages that a cursor should
     * have considering the $this->perPage
     *
     * @param $cursor An OdmCursor
     * @return int Ammount of pages
     */
    public function pageCount( $cursor )
    {
        return round($cursor->count()/$this->perPage);
    }

    /**
     * Return the $cursor paginated using the $perPage
     * attribute. A $page may be specified in order to
     * skip some of the product
     *
     * @param $cursor The cursor to be paginated
     * @param $page The page that are gonna be returned
     * @return OdmCursor Paginated cursor.
     */
    public function paginate( $cursor, $page = null )
    {
        if(! $page)
            $page = 1;

        return $cursor->limit( $this->perPage )
            ->skip( ($page-1)*$this->perPage );
    }

    /**
     * Saves a new instance into the database and return
     * the save result (that will run the isValid) since
     * Product extends from the BaseModel.
     *
     * @param $instance Non saved Product instance
     * @return Boolean The result of the instance save() method
     */
    public function createNew( Product &$instance )
    {
        // Since is a NEW product. Clear the _id if it exists
        unset($instance->_id);
        
        // Apply the polymorph manually before saving
        // This way the specific validation will play their role
        // before saving the model
        $instance = $instance->polymorph( $instance );

        // Save the instance
        $result = $instance->save();

        return $result;
    }

    /**
     * Returns one product with the $id
     *
     * @param $id Id or query
     * @return Product
     */
    public function first( $id )
    {
        return Product::first($id);
    }

    /**
     * Returns the result of an $instance->save()
     *
     * @param $instance Product instance to be saved
     * @return Boolean save() result
     */
    public function update( Product &$instance )
    {
        return $instance->save();
    }

    /**
     * Validates and update the characteristics of a product
     * following the validation for each tipe of characteristic
     * present in the product category.
     *
     * @param $instance Product instance to be updated
     * @param $characValues Array containing the values to be set into the Product details
     * @return Boolean save() result
     */
    public function updateCharacteristics( Product &$instance, $characValues = array() )
    {
        // Product details attribute
        $details = array();

        // For each characteristic in the category
        foreach ($instance->category()->characteristics() as $charac) {

            // Get input
            $details[clean_case($charac->name)] = array_get( $characValues, clean_case($charac->name));

            if(! $details[clean_case($charac->name)])
                $details[clean_case($charac->name)] = array_get( $characValues, str_replace(' ', '_', clean_case($charac->name)));
        }
        
        // Finally set the details attribute
        $instance->details = $details;

        // Saves
        return $instance->save();
    }
}
