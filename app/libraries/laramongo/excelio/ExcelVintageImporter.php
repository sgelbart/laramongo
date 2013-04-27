<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, ConjugatedProduct, Category, Illuminate\Support\MessageBag;

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
        'LMs Conjugados' => 'products',
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
     * Reads the category if of the excel file that is being imported
     * @param  PHPExcel $excel    Openned excel file
     * @return string String of the MongoId of the found category 
     */
    protected function parseCategory($excel)
    {
        $aba1 = $excel->setActiveSheetIndex(0);
        
        $departmentName = $aba1->getCell('B3')->getCalculatedValue();
        $departmentSlug = ruby_case($departmentName);
        $department = Category::first(['slug'=>$departmentSlug]);

        if(! $department)
        {
            $department = new Category;
            $department->name = $departmentName;
            $department->slug = $departmentSlug;
            $department->save();
        }

        $familyName = $aba1->getCell('B4')->getCalculatedValue();
        $familySlug = ruby_case($familyName);
        $family = Category::first(['slug'=>$familySlug]);

        if(! $family)
        {
            $family = new Category;
            $family->name = $familyName;
            $family->slug = $familySlug;
            $family->attachToParents($department);
            $family->save();
        }

        $keyId = str_pad(
            $aba1->getCell('C5')->getCalculatedValue(),
            6, '0', STR_PAD_LEFT
        );

        $keyName = $aba1->getCell('B5')->getCalculatedValue();
        $keySlug = ruby_case($keyName);
        
        // If not from the same parent, then it's a different category
        $key = Category::first(['slug'=>$keySlug, 'parents'=>$family->_id]);

        if(! $key)
        {
            $key = new Category;
            $key->name = $keyName;
            $key->slug = $keySlug;
            $key->kind = 'leaf';
            $key->attachToParents($family);

            $key->_id = $keyId;
            $key->image = array_get(\ImageGrabber::grab($key),0,null); // Grab Category Image
            unset($key->_id);

            $key->save();
        }

        return $key->_id;
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
