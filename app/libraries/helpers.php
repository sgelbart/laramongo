<?php

if ( ! function_exists('l'))
{
    /**
     * Get the translation for the given key.
     * PS: Alias to Lang::get
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    function l($key, $replace = array(), $locale = null)
    {
        return Lang::get($key, $replace, $locale);
    }
}

if ( ! function_exists('escape_for_js'))
{
    /**
     * Escapes the content in order to be used as a string
     * in Javascript.
     */
    function escape_for_js($content)
    {
        return str_replace("\n",'\n', str_replace('"','\"', $content ));
    }
}

if ( ! function_exists('view_vars'))
{
    /**
     * By using this function in a view an array
     * containing all the variables passed to the view
     *
     */
    function view_vars($__data)
    {
        $result = array();

        foreach ($__data as $key => $value) {
            if($key != '__env')
            {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}

if ( ! function_exists('clean_case'))
{
    /**
     * Remove acents then convert string to clean_case
     *
     * @param  string  $value
     * @return string
     */
    function clean_case($value){

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

        return strtolower($value);
    }
}

if ( ! function_exists('ruby_case'))
{
    /**
     * Remove acents then convert string to clean_case but
     * replaces the spaces with underline
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    function ruby_case($value){
        $tags = [
            ' ',
            '/',
            '\\'
        ];

        return str_replace($tags, "_", clean_case($value));
    }
}
