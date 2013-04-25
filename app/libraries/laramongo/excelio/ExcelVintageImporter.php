<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Category, Illuminate\Support\MessageBag;

class ExcelVintageImporter extends ExcelImporter {

    /**
     * Array with relative vintage names for the current attributes
     */
    protected $relativeVintageName = [
        'LM' => '_id',
        'Titulo' => 'name',
        'Título' => 'name',
        'Texto Publicitário' => 'description',
        'Descrição' => 'small_description',
    ];

    /**
     * The line where the attributes/schema of the chave the entrada
     * can be found
     * @return integer Excel file line number
     */
    protected function attributeRow()
    {
        return 7;
    }

    /**
     * Reads the category if of the escel file that is being imported
     * @param  PHPExcel $excel    Openned excel file
     * @return string String of the MongoId of the found category 
     */
    protected function parseCategory($excel)
    {
        $aba1 = $excel->setActiveSheetIndex(0);
        
        $categoryName = $aba1->getCell('B5')->getCalculatedValue();
        $categoryName = ruby_case($categoryName);
        $category = Category::first(['slug'=>$categoryName]);

        if(! $category)
        {
            $category = new Category;
            $category->name = $categoryName;
            $category->slug = $categoryName;
            $category->kind = 'leaf';
            $category->save();
        }

        return $category->_id;
    }
    /**
     * Returns an Product object with the contents of the line read
     * based on the specified schema
     * 
     * @param  PHPExcel $excel Openned excel file
     * @param  integer $line   Line Number
     * @param  array $schema   The fields present in the excel file
     * @return Product         The product object build from the line content
     */
    protected function parseLine($excel, $line, $schema)
    {
        $aba1 = $excel->setActiveSheetIndex(0);
        $product = new Product;

        foreach ($schema as $x => $attribute) {

            $attrName = substr($attribute,0,-1);

            if(isset($this->relativeVintageName[$attrName]))
            {
                $attrName = $this->relativeVintageName[$attrName];
            }

            if(in_array($attrName, $this->nonCharacteristicKeys))
            {
                $product->setAttribute($attrName, $aba1->getCellByColumnAndRow($x, $line)->getValue());
            }
            else
            {
                $attrName = clean_case($attrName);

                $value = $aba1->getCellByColumnAndRow($x, $line)->getValue();

                if($value)
                {
                    $details = $product->getAttribute('details');
                    $details[$attrName] = ucfirst($value);
                    $product->setAttribute('details', $details);
                }
            }
        }

        return $product;   
    }

}
