<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Illuminate\Support\MessageBag;

class ExcelImporter extends ExcelIo {
    
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
     * Imports an excel file in the new format (Containing "Category: MongoId")
     * or in the vintage format (Containing "Chave de entrada", "Familia", etc)
     * If the file is being imported in the vintage format, the Chave de entrada
     * name will be used to determine to what category the file is being uploaded
     * 
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
            strtolower($aba1->getCell('A2')->getCalculatedValue()) != 'categoria';

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

                if(in_array($attrName, $this->nonCharacteristicKeys))
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
                $product->isValid(); // Fill the errors of the object
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
}
