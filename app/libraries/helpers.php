<?php

if ( ! function_exists('clean_case'))
{
    /**
     * Remove acents then convert string to clean_case
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    function clean_case($value, $delimiter = '_'){

        $normalizeChars = array( 
            'Á'=>'a', 'À'=>'a', 'Â'=>'a', 'Ã'=>'a', 'Å'=>'a', 'Ä'=>'a', 'Æ'=>'ae', 'Ç'=>'c', 
            'É'=>'e', 'È'=>'e', 'Ê'=>'e', 'Ë'=>'e', 'Í'=>'i', 'Ì'=>'i', 'Î'=>'i', 'Ï'=>'i', 'Ð'=>'eth', 
            'Ñ'=>'n', 'Ó'=>'o', 'Ò'=>'o', 'Ô'=>'o', 'Õ'=>'o', 'Ö'=>'o', 'Ø'=>'o', 
            'Ú'=>'u', 'Ù'=>'u', 'Û'=>'u', 'Ü'=>'u', 'Ý'=>'y', 
    
            'á'=>'a', 'à'=>'a', 'â'=>'a', 'ã'=>'a', 'å'=>'a', 'ä'=>'a', 'æ'=>'ae', 'ç'=>'c', 
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e', 'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'eth', 
            'ñ'=>'n', 'ó'=>'o', 'ò'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 
            
            'ß'=>'sz', 'þ'=>'thorn', 'ÿ'=>'y' 
        ); 

        $value = strtr(
            $value,
            $normalizeChars
        );

        return snake_case($value, $delimiter);
    }
}
