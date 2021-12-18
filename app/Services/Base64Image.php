<?php

namespace App\Services;
use App\Services\Service;
use Exception;

class Base64Image extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function save($image, $path, $filename) 
    {
        $folderPath = $path;
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $filename=$filename;
        $file = $folderPath . $filename;
        file_put_contents($file, $image_base64);
    }
    
    public function saveReturn($image, $path, $filename,$type) 
    {
        $folderPath = $path;
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode($type."/",$image_parts[0] );
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $filename=$filename.'.'.$image_type;
        $file = $folderPath . $filename;
        file_put_contents($file, $image_base64);
        return $filename;
    }
    
    public function delete($path, $filename) 
    {
        try{
            $folderPath = $path;
            $file = $folderPath . $filename;
            unlink($file);
        }catch(Exception $e){}
    }

    public function compress($file){
        if (!file_exists(storage_path('app/public/reduce/'.$file))) {
            try{
                
            $info = getimagesize(storage_path('app/public/'.$file));
            if ($info['mime'] == 'image/jpeg') 
                $image = imagecreatefromjpeg(storage_path('app/public/'.$file));

            elseif ($info['mime'] == 'image/gif') 
                $image = imagecreatefromgif(storage_path('app/public/'.$file));

            elseif ($info['mime'] == 'image/png') 
                $image = imagecreatefrompng(storage_path('app/public/'.$file));

            imagejpeg($image, storage_path('app/public/reduce/'.$file), 60);
            }catch(Exception $e){}
            
        }

        
    }
    public function check($file){
        if (!file_exists(storage_path('app/public/reduce/'.$file)) && !file_exists(storage_path('app/public/'.$file))) {
            return true;
            
        }

        return false;
    }
}