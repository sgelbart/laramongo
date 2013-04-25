<?php namespace Laramongo\ExcelIo;

use PHPExcel, PHPExcel_IOFactory, PHPExcel_Style_Fill, PHPExcel_Style_Color, PHPExcel_Style_Border, PHPExcel_Reader_Excel2007;
use Product, Illuminate\Support\MessageBag;

abstract class ExcelIo {

    /**
     * Array of attributes that are not characteristics. This attributes
     * will not be embedded in the details attribute.
     */
    protected $nonCharacteristicKeys = [
        '_id','name','description','small_description','category','products','conjugated'
    ];

    /**
     * Return a array to apply a background color in excel cels
     * 
     * @param  string $hex   Hexadecimal color (whitout '#')
     * @return array        The array to be applied
     */
    protected function fillColor( $hex )
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
    protected function borderColor( $hex , $style = 'outline' )
    {
        return [$style => [
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $hex),
        ]];
    }

}
