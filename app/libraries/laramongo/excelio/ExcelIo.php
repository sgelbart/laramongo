<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Illuminate\Support\MessageBag;

class ExcelIo {

    /**
     * Array of attributes that are not characteristics. This attributes
     * will not be embedded in the details attribute.
     */
    protected $non_characteristic_keys = [
        '_id','name','description','small_description','category','products'
    ];

    /**
     * Errors that happened during the import
     *
     * @var array
     */
    protected $errors;

    /**
     * Successfuly imported models
     *
     * @var array
     */
    protected $success;

    /**
     * Export the given $category to an excel file saved as $output
     * @param  Category $category Leaf category to be exported
     * @param  string   $output   Destination file relative to the app directory
     * @return bool Success
     */
    public function exportCategory( $category, $output )
    {
        $excel = new PHPExcel;

        $excel->getProperties()
            ->setCreator('Laramongo')
            ->setTitle($category->name)
            ->setCategory($category->_id);

        $this->renderCategory($category, $excel);

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save( app_path().'/'.$output );

        return true;
    }

    /**
     * Imports an excel file in the new format (Containing "Category: MongoId")
     * or in the vintage format (Containing "Chave de entrada", "Familia", etc)
     * If the file is being imported in the vintage format, the Chave de entrada
     * name will be used to determine to what category the file is being uploaded
     * @param  string $path File to be imported
     * @return bool Success
     */
    public function importFile( $path )
    {        
        $reader = new PHPExcel_Reader_Excel2007();

        if( $reader->canRead(app_path().'/'.$path) )
        {
            $excel = $reader->load( app_path().'/'.$path );
            return $this->parseFile( $excel );
        }
        else
        {
            return false;
        }
    }

    /**
     * Read, isntantiate Products for each line and save then into database
     * 
     * @param  PHPExcel $excel    Openned excel file
     * @return bool Success
     */
    protected function parseFile( $excel )
    {
        $vintage = false; // Vintage means that the excel file is provenient of
                          // the old website.

        $aba1 = $excel->setActiveSheetIndex(0);

        // Enable vintage mode if the A2 content is not 'Categoria'

        $vintage =
            $aba1->getCell('A2')->getCalculatedValue() != 'Categoria';

        $attributesRow = ($vintage) ? 7 : 4;

        // Read schema

        $schema = array();

        $x = 0;
        while($aba1->getCellByColumnAndRow($x, $attributesRow)->getValue())
        {
            $schema[] = $aba1->getCellByColumnAndRow($x, $attributesRow)->getValue()."\n";
            $x++;
        }

        // Import lines

        $y = $attributesRow+1;
        while( $aba1->getCellByColumnAndRow(1, $y)->getValue() )
        {   
            $product = new Product;

            foreach ($schema as $x => $attribute) {

                $attrName = substr($attribute,0,-1);

                if(in_array($attrName, $this->non_characteristic_keys))
                {
                    $product->setAttribute(substr($attribute,0,-1), $aba1->getCellByColumnAndRow($x, $y)->getValue());
                }
                else
                {
                    $value = $aba1->getCellByColumnAndRow($x, $y)->getValue();

                    if($value)
                    {
                        $details = $product->getAttribute('details');
                        $details[$attrName] = ucfirst($value);
                        $product->setAttribute('details', $details);
                    }
                }
            }

            if(! $vintage)
            {
                if(! isset($targetCategory))
                    $targetCategory = $aba1->getCell('B2')->getCalculatedValue();

                $product->category = $targetCategory;
            }

            if($product->_id)
            {
                $product->save( true );
            }
            else
            {
                $product->errors = new MessageBag(['_id','Produto sem LM']);
            }

            if( ! $product->errors )
            {
                $this->success[] = $product->_id;
            }
            else
            {
                $failedProduct = $product->toArray();
                $failedProduct['error'] = isset($product->errors) ? $product->errors->all() : 'Erro fatal';
                $this->errors[] = $failedProduct;
            }

            $y++;
        }

        return true;
    }

    /**
     * Renders an category to a excel file
     * 
     * @param  Category $category Leaf category to be exported
     * @param  PHPExcel $excel    Openned excel file
     * @return bool Success
     */
    protected function renderCategory( $category, $excel )
    {
        // Render control vars

        $aba1 = $excel->setActiveSheetIndex(0);

        $aba1
            ->setCellValue('A2','Categoria')
            ->setCellValue('B2',(string)$category->_id);
            
        $aba1->getStyle('A2:B2')->getFill()->applyFromArray($this->fillColor('A8F263'));
        $aba1->getStyle('A2:B2')->getBorders()->applyFromArray($this->borderColor('599E19', 'allborders'));

        // Render headers

        $products = \Product::where(['category'=>(string)$category->_id]);
        $schema = $this->buildSchema( $category, $products );

        $x = 0;
        foreach ($schema as $characteristic => $defined) {
            $aba1->setCellValueByColumnAndRow($x, 4, $characteristic);

            if(in_array($characteristic, $this->non_characteristic_keys)){
                $color = 'F0AF59';
            }elseif($defined){
                $color = 'F0DE59';
            }else{
                $color = 'BDB893';   
            }

            $aba1->getStyleByColumnAndRow($x, 4)->getFill()->applyFromArray($this->fillColor($color));
            $aba1->getStyleByColumnAndRow($x, 4)->getBorders()->applyFromArray($this->borderColor('80720B', 'allborders'));

            $x++;
        }

        // Render products

        $y = 5; // Since the line 4 is the headers, start drawing products from line 5 and beyond
        foreach ($products as $product) {
            $x = 0;
            foreach ($schema as $key => $val) {
                if( in_array($key, $this->non_characteristic_keys) ){
                    $val = $product->$key;
                }
                else{
                    $val = array_get($product->details, $key, '');
                }

                $aba1->setCellValueByColumnAndRow($x, $y, (string)$val);
                $aba1->getStyleByColumnAndRow($x, $y)->getBorders()
                    ->applyFromArray($this->borderColor('AAB3A1', 'allborders'));

                $x++;
            }
            $y++;
        }
    }

    /**
     * Builds a schema (array of columns) containing all the category
     * characteristics and also all other details that are present in
     * $products
     * In the array returned, the keys will be the characteristic name
     * and the value will be true or false. True when the characteristic
     * is defined in the Category.
     * 
     * @param  Category  $category Category to map the characteristics
     * @param  OdmCursor $products OdmCursor of Product
     * @return array Array containing all the columns
     */
    public function buildSchema( $category, $products = null )
    {
        $characs = $category->characteristics();

        $result = array();

        foreach ($this->non_characteristic_keys as $key) {
            $result[$key] = true;
        }

        // Append the product detail keys as false
        if($products)
        {
            foreach ($products as $product) {
                if( is_array($product->details) )
                {
                    foreach (array_keys($product->details) as $key) {
                        $result[$key] = false;
                    }
                }
            }
        }

        // Append or set the category characteristics as true
        foreach ($characs as $charac) {
            $result[clean_case($charac->name)] = true; 
        }

        return $result;
    }

    /**
     * Retrieve the models that coudn't be imported. All of then
     * are filled with an ErrorBag in error attribute
     *
     * @return array
     */
    public function getErrors()
    {
        return (array)$this->errors;
    }

    /**
     * Retrieve the models that have been imported
     *
     * @return array
     */
    public function getSuccess()
    {
        return (array)$this->success;
    }

    /**
     * Return a array to apply a background color in excel cels
     * 
     * @param  string $hex   Hexadecimal color (whitout '#')
     * @return array        The array to be applied
     */
    private function fillColor( $hex )
    {
        return [
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => $hex)
        ];
    }

    /**
     * Return a array to apply a border color in excel cels
     * @param  string $hex   Hexadecimal color (whitout '#')
     * @param  string $style Border style, may be 'allborders' or 'outline'
     * @return array        The array to be applied
     */
    private function borderColor( $hex , $style = 'outline' )
    {
        return [$style => [
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $hex),
        ]];
    }

}
