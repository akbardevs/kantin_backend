<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;

// Eloquent models
use App\Models\Hotline;
use App\Models\Manual;
use App\Models\Reference;

class OneService extends Service
{
  public function __construct($resourceType)
  {
    $this->resourceType = $resourceType;
  }

  public function execute()
  {
  }

  public function get($id)
  {
    switch ($this->resourceType) {
      // hotlines don't have single post page.
      case "Reference":
        return $this->getResourceById(Reference::class, $id)->toArray();
      case "Manual":
        return $this->getResourceById(Manual::class, $id)->toArray();
    }
  }

  public function getResourceById($model, $id)
  {
    return $model
      ::with(["category:id,parent_id,name", "user:id,user_group_id,role"])->with((['district' => function ($query) {
        $query->select('id', 'name','city_id')->with((['city' => function ($query2) {
          $query2->select('id', 'name','province_id')->with('province');
      }]));
    }]))
      ->findOrFail($id); // return with category and user (author) data
  }
}
