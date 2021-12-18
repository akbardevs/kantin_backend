<?php

namespace App\Services;
use App\Services\Service;

class GetDataFB extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function get($token) 
    {
        $client = new \GuzzleHttp\Client();
        $fields = 'id,email,first_name,last_name,link,name,picture.type(large)';
        $profileResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/me', [
        'query' => [
            'access_token' => $token,
            'fields' => $fields
        ]
        ]);
        $data = $profileResponse->getBody();
        $manage = json_decode($data, true);
        return  $manage;
    }
}