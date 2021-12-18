<?php

namespace App\Services;

use App\Models\Post;
use App\Services\Service;

class PostService extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function save($type, $id) 
    {
        if($type == 'sharing'){
            $save = Post::create([
                'type' => $type,
                'data_id' => $id
            ]);
        } else if($type == 'qa'){
            $save = Post::create([
                'type' => $type,
                'data_id' => $id
            ]);
        } else {
            $save = Post::create([
                'type' => $type,
                'data_id' => $id
            ]);
        }
        return $save;
    }
}