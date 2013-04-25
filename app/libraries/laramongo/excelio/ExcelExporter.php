<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Illuminate\Support\MessageBag;

class ExcelExporter extends ExcelIo {

    /**
     * Export the given $category to an excel file saved as $output
     * 
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

        $slug = ($category->slug) ?: ruby_case($category->name);

        $aba1
            ->setCellValue('A2','Categoria')
            ->setCellValue('B2',$slug);
            
        $aba1->getStyle('A2:B2')->getFill()->applyFromArray($this->fillColor('A8F263'));
        $aba1->getStyle('A2:B2')->getBorders()->applyFromArray($this->borderColor('599E19', 'allborders'));

        // Render headers

        $products = \Product::where(['category'=>(string)$category->_id]);
        $schema = $this->buildSchema( $category, $products );

        $x = 0;
        foreach ($schema as $characteristic => $defined) {
            $aba1->setCellValueByColumnAndRow($x, 4, $characteristic);

            if(in_array($characteristic, $this->nonCharacteristicKeys)){
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
                if( in_array($key, $this->nonCharacteristicKeys) ){
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

        foreach ($this->nonCharacteristicKeys as $key) {
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

}
