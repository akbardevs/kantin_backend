<?php

namespace App\Services;
use App\Models\Media;
use App\Services\Service;
use App\Services\Base64Image;

class SaveFilePost extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function save($request, $save) 
    {
        try{
            $upload = new Base64Image;
            $media = Media::where('post_id',$save['id'])->get();
            if($media && $media!=null){
                foreach($media as $data){
                    if($data->type=='image')$upload->delete(storage_path('app/public/'),substr($data->data,11));
                    $data->delete(); 
                }
            }
            if($request['type']){
                if($request['type']=='image'){
                    $count=1;
                    $dataImage = explode('--',$request['data']);
                    foreach($dataImage as $image){
                        $name = time().$count.$save->id;
                        $nameFile = $upload->saveReturn($image ,storage_path('app/public/'),$name,$request['type']);
                        $db= Media::create(['post_id'=>$save->id,'data'=>$nameFile,'type'=>$request['type']]);
                        $count++;
                    }
                } else if($request['type']=='url'){
                    $nameFile = $request['data'];
                    $db= Media::create(['post_id'=>$save->id,'data'=>$nameFile,'type'=>$request['type']]);
                } else {
                    $name = time().$save->id;
                    $nameFile = $upload->saveReturn($request['data'] ,storage_path('app/public/'),$name,$request['type']);
                    $db= Media::create(['post_id'=>$save->id,'data'=>$nameFile,'type'=>$request['type']]);
                }
            }
            
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with("error", [
                "from" => "input promotion",
                "data" => $errorMsg
            ]); 
        }
    }
    
}