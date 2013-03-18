<?php namespace Traits;

use URL;

trait HasImage
{
    /**
     * Attach an UploadedFile as the category image
     * 
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $image_file
     * @return bool
     */
    public function attachUploadedImage( $image_file )
    {
        $path = app_path().'/'.$this->images_path;
        $filename = $this->_id.'.jpg';

        $old = umask(0); 

        if ( ! is_dir($path) )
            mkdir($path, 0777, true);
        
        $image_file->move($path, $filename);
        try{
            chmod($path.'/'.$filename, 0775);    
        }catch( \Exception $e){}

        umask($old); 

        $this->image = $filename;
        
        return $this->save();
    }

    /**
     * Return image URL
     *
     * @return string
     */
    public function imageUrl()
    {
        if( $this->image )
        {
            return URL::to('assets/img/categories/'.$this->image);
        }
        else
        {
            return URL::to('assets/img/categories/default.png');
        }
    }
}
