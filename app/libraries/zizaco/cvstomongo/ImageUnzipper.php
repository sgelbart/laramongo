<?php namespace Zizaco\CsvToMongo;

use VIPSoft\Unzip\Unzip;

class ImageUnzipper
{

    /**
     * VIPSoft Unzip
     *
     * @var VIPSoft\Unzip\Unzip
     */
    protected $unzipper;

    /**
     * Path where category images will be stored
     *
     * @var string
     */
    private $images_path = '../public/uploads/img/products';

    /**
     * File that will be unzipped
     *
     * @var string
     */
    private $file;

    /**
     * The file that should be extracted
     * 
     * @param string $file
     * @return void
     */
    public function __construct( $file )
    {
        $this->unzipper  = new Unzip();
        $this->file = $file;
    }

    /**
     * Extract the file contents
     *
     * @return mixed Value.
     */
    public function extract()
    {
        $path = app_path().'/'.$this->images_path;

        $old = umask(0); 

        if ( ! is_dir($path) )
            mkdir($path, 0777, true);

        $filenames = $this->unzipper->extract( $this->file, $path );

        try{
            foreach ($filenames as $filename) {
                chmod($path.'/'.$filename, 0775);
                $this->sendImageToNas( $filename );
            }
        }catch( Exception $e){
            umask($old);
            return false;
        }

        umask($old);
        return true;
    }

    /**
     * Send image to the Nas service
     */
    protected function sendImageToNas( $image )
    {
        if(\Config::get('s3.enabled',false))
        {

            $public_path = 'uploads/img/products/'.$this->image;
            $full_path = app_path().'/../public/'.$public_path;

            $s3 = new \Laramongo\Nas\S3;
            $result = $s3->sendFile($public_path);    

            if($result)
            {
                unlink($full_path);
            }
        }

        return true;
    }
}
