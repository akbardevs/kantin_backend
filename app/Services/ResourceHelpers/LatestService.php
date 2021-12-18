<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;

// Eloquent models
use App\Models\Manual;
use App\Models\ManualCategory;
use App\Models\Reference;
use App\Models\ReferenceCategory;
use App\Models\User;
use App\Models\UserGroup;

class LatestService extends Service
{
  const OPTIONS = [
    "SELECT_COLUMNS" => ["id", "title", "content", "category_id", "created_at", "main_image", "available_for","user_id"], // prettier-ignore
  ];

  const ORDER_BY = ["created_at", "desc"];

  public function __construct($count = 5)
  {
    $this->count = $count;
  }

  public function execute()
  {
  }

  public function getReferences()
  {
    $cerita_dampak = Reference::select(self::OPTIONS["SELECT_COLUMNS"])
    ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
    ->limit($this->count)
    ->addSelect([
      "category_name" => ReferenceCategory::select("name")->whereColumn("id", "references.category_id")->limit(1), // prettier-ignore
    ])->where('category_id',3)
    ->get();
    if($cerita_dampak->count()>0){
      $newLimit = $this->count - $cerita_dampak->count();
      $addRef = Reference::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit($newLimit)
      ->addSelect([
        "category_name" => ReferenceCategory::select("name")->whereColumn("id", "references.category_id")->limit(1), // prettier-ignore
      ])->where('category_id','!=',3)
      ->get();
      foreach($addRef as $ref){
        $cerita_dampak->push($ref);
      }
      return $cerita_dampak;
    } else {
      return Reference::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit($this->count)
      ->addSelect([
        "category_name" => ReferenceCategory::select("name")->whereColumn("id", "references.category_id")->limit(1), // prettier-ignore
      ])
      ->get();

    }
  }

  public function getManuals()
  {
    return $this->getResourcesLatest("manuals", Manual::class, ManualCategory::class); // prettier-ignore
  }


  public function getResourcesLatest($plural, $model, $catModel)
  {   
    if(auth()->user()&&auth()->user()!=null){
      $dataReturn=[];
      $data = $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id", $plural . ".category_id")->limit(1), // prettier-ignore
        "user_group" => User::select("user_group_id")->whereColumn("id", $plural . ".user_id")->limit(1), // prettier-ignore
      ])
      ->get();
      foreach($data as $dataUser){
        if(count($dataReturn)<5 && $dataUser->user_group==auth()->user()->user_group_id && $dataUser->available_for==1){
          array_push($dataReturn,$dataUser);
        }
      }
      if(count($dataReturn)!=5){
        for($i=0;$i<count($data);$i++){
          if($data[$i]->available_for==0){
            array_push($dataReturn,$data[$i]);
            if(count($dataReturn)==5){
              $i=count($data);
            }
          }
        }
      }
      return $dataReturn;
    }else{
      return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->where('available_for','0')
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit($this->count)
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id", $plural . ".category_id")->limit(1), // prettier-ignore
      ])
      ->get();
    }
    
  }
}
