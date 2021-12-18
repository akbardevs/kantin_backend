<?php

namespace App\Services;
use App\Services\Service;
use Torann\LaravelMetaTags\Facades\MetaTag;

class MetaTagServices extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function set($data) 
    {
        if($data['title']!=null){
            MetaTag::set('title', $data['title']);
            // MetaTag::set('title', 'Penatani.id');
        }
        
        if($data['desc']!=null){
            MetaTag::set('description', $data['desc']);
            // MetaTag::set('description', 'Menumbuhkan Alam dan Tradisi. Yuk, saling berbagi informasi, tanya jawab, ataupun promosi produk di Penatani.id!');
        }
        // MetaTag::set('image', url(config('app.url').'/api/image/penatani.jpeg'));
        if($data['post']['media']!=null){
            if(count($data['post']['media'])>=1){
                MetaTag::set('image', url(config('app.url').$data['post']['media'][0]['data']));
            } else {
                MetaTag::set('image', url(config('app.url').'/api/image/penatani.jpeg'));
            }
        } else {
            MetaTag::set('image', url(config('app.url').'/api/image/penatani.jpeg'));
        }
        
    }

    public function general() 
    {
        MetaTag::set('title', 'Penatani.id');
        MetaTag::set('description', 'Menumbuhkan Alam dan Tradisi. Yuk, saling berbagi informasi, tanya jawab, ataupun promosi produk di Penatani.id!');
        MetaTag::set('image', url(config('app.url').'/api/image/penatani.jpeg'));
    }


    
    
}