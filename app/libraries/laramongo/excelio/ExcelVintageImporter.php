<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Illuminate\Support\MessageBag;

class ExcelVintageImporter extends ExcelImporter {

    /**
     * The line where the attributes/schema of the chave the entrada
     * can be found
     * @return integer Excel file line number
     */
    public protected function attributeRow()
    {
        return 7;
    }

}
