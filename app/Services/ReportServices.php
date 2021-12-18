<?php

namespace App\Services;
use App\Models\Report;
use App\Services\Service;
use Illuminate\Support\Facades\Request;

class ReportServices extends Service
{
    public function __construct()
    {
        
    }

    public function execute() 
    {
      
    }
    
    public function save($request,$user_id,$type) 
    {
        $check=Report::where([['user_id',$user_id],['id_data',$request->id],['type',$type]])->first();
        // if($check) return 0;
        Report::create($request->all());
    }
}