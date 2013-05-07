<?php namespace Traits;

use URL, Asset;

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
        $path = app_path().'/../public/uploads/img/'.$this->collection;
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

        if($this->sendImageToNas())
        {
            return $this->save();
        }
        else
        {
            return false;
        }
    }

    protected function sendImageToNas()
    {
        if(\Config::get('s3.enabled',false))
        {

            $public_path = 'uploads/img/'.$this->collection.'/'.$this->image;
            $full_path = app_path().'/../public/'.$public_path;

            $s3 = app()->s3;
            if( $s3 )
            {
                $result = $s3->sendFile($public_path);

                if($result)
                {
                    unlink($full_path);
                }

                return $result;
            }
        }

        return true;
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
            return URL::to(Asset::url('uploads/img/'.$this->collection.'/'.$this->image));
        }
        else
        {
            return URL::to(Asset::url('assets/img/'.$this->collection.'/default.png'));
        }
    }

    public function hasImage()
    {
        return (boolean)$this->image;
    }
}
