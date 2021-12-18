<?php

namespace App\Services;
use App\Models\Post;
use App\Models\Topic;
use App\Services\Service;

class FindTopic extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function get($page) 
    {
        if($page == 'home'){
            $post = Post::with('media')->with(['information.user','qa.user','promotion.user'])->orderBy('created_at','desc')->get();
        } else if($page == 'sharing'){
            $post = Post::with('media')->with(['information.user'])->orderBy('created_at','desc')->where('type','sharing')->get();
        } else if($page == 'qa'){
            $post = Post::with('media')->with(['qa.user'])->orderBy('created_at','desc')->where('type','qa')->get();
        } else if($page == 'promotion'){
            $post = Post::with('media')->with(['promotion.user'])->orderBy('created_at','desc')->where('type','promotion')->get();
        }
        $dataa = [];
        $topic = Topic::where('status',1)->orderBy('sort','asc')->get();
        
            foreach($topic as $dataTopic){
                foreach($post as $data){
                if($data['promotion']){
                    if($page == 'home' || $page == 'promotion'){
                        $topicData = null;
                        try {
                            $topicData = $data['information']['topic_id'][0];
                        } catch (\Throwable $th) {}
                        if($dataTopic->id == $topicData && $data['promotion']['status']==1 ){
                            $getArr = array_search($dataTopic->id, array_column($dataa, 'id'));
                            if( $getArr  === false){
                                array_push($dataa,$dataTopic);  
                            }  
                        }
                    }
                }
                if($data['qa']){
                    if($page == 'home' || $page == 'qa'){
                        $topicData = null;
                        try {
                            $topicData = $data['information']['topic_id'][0];
                        } catch (\Throwable $th) {}
                        if($dataTopic->id == $topicData && $data['qa']['status']==1){
                            $getArr = array_search($dataTopic->id, array_column($dataa, 'id'));
                            if( $getArr  === false){
                                array_push($dataa,$dataTopic);  
                            }  
                        }
                    }
                }
                
                if($data['information']){
                    if($page == 'home' || $page == 'sharing'){
                        $topicData = null;
                        try {
                            $topicData = $data['information']['topic_id'][0];
                        } catch (\Throwable $th) {}
                        if($dataTopic->id == $topicData && $data['information']['status']==1){
                            $getArr = array_search($dataTopic->id, array_column($dataa, 'id'));
                            if( $getArr  === false){
                                array_push($dataa,$dataTopic);  
                            }  
                        }
                    }
                }
                
            }
        }
        return $dataa;
    }
    
}