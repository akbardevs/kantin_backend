<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Post;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Torann\LaravelMetaTags\Facades\MetaTag;

class NotifUpdate extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function data($id,$type,$count,$title,$user_id,$type_post) 
    {
        try {
            $post = Post::where([['data_id',$id],['type',$type_post]])->first();
        if($post){
            if($type!='like')$dataNotif = Notification::where([['post_id',$post->id],['type',$type]])->first(); else $dataNotif = Notification::where('post_id',$post->id)->first();
            if(!$dataNotif){
                if(Auth::id()){
                    $iduser = Auth::id();
                    // if($user_id==Auth::id())$iduser = array(Auth::id()); else $iduser = array($user_id,Auth::id());
                    if($user_id==Auth::id())$iduser = array($user_id); else $iduser = array($user_id,Auth::id());
                    Notification::create([
                        'post_id' => $post->id,
                        'title' => $title,
                        'type' => $type,
                        'user_follow' => $iduser,
                        'count'=>$count,
                        'user_unfollow'=>array(),
                        'user_read'=>array(Auth::id()),
                        'user_id'=>null,
                    ]);
                } else {
                    Notification::create([
                        'post_id' => $post->id,
                        'title' => $title,
                        'type' => $type,
                        'count'=>$count,
                        'user_follow' => array($user_id),
                        'user_unfollow'=>array(),
                        'user_read'=>array(),
                        'user_id'=>null,
                    ]);
                }
            } else {
                $userArr = $dataNotif['user_follow'];
                if(Auth::id()){
                    
                    $dataNotif['count']=$count;
                    if (in_array($user_id, $userArr))
                    {
                        $notifCreator=array_values(array_diff( $userArr, [$user_id] ));
                        // array_push($userArr,Auth::id());
                        // array_push($userArr,Auth::id());
                    }
                    $dataNotif['user_follow'] = $userArr;
                    $dataNotif['user_read']=$notifCreator;
                } else {
                    if (in_array($user_id, $userArr))
                    {
                        $notifCreator=array_values(array_diff( $userArr, [$user_id] ));
                    }
                    $dataNotif['count']=$count;
                    $dataNotif['user_read']=$notifCreator;
                }
                $dataNotif->save();
            }
         return array(['okok'=>$dataNotif],['id'=> $id]);   
        } else return $id;
        } catch (\Exception $e) {
            return $e;
        }
        
        
        
    }

    public function stopLike(){
        
    }
    
    
}