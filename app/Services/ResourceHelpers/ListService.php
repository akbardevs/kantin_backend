<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;

// Eloquent models
use App\Models\Hotline;
use App\Models\HotlineCategory;
use App\Models\Manual;
use App\Models\ManualCategory;
use App\Models\Reference;
use App\Models\ReferenceCategory;
use App\Models\User;

// !! NOT USED, ListPaginate is used instead.

class ListService extends Service
{
  const OPTIONS = [
    "LIMIT_COUNT" => 6,
    "SELECT_COLUMNS" => ["id", "title", "category_id", "created_at", "main_image", "available_for", "user_id"], // prettier-ignore
  ];

  const ORDER_BY = ["created_at", "desc"];

  public function __construct($resourceType)
  {
    $this->resourceType = $resourceType;
  }

  public function execute()
  {
  }

  public function all($catId = null)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        $collection = $this->getResourcesAll('hotlines', Hotline::class, HotlineCategory::class, $catId);
        break;
      // prettier-ignore
      case "Manual":
        $collection = $this->getResourcesAll('manuals', Manual::class, ManualCategory::class, $catId);
        break;
      // prettier-ignore
      case "Reference":
        $collection = $this->getResourcesAll('references', Reference::class, ReferenceCategory::class, $catId);
        break;
      default:
        return [];
    }
    if (!$collection->count()) return []; // prettier-ignore
    return $collection->toArray();
  }

  private function getResourcesAll($plural, $model, $catModel, $catId = null)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catId, function ($query) use ($catId) {
        $query->where("category_id", $catId);
      })
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->get();
    // ->paginate(self::OPTIONS["LIMIT_COUNT"])
    // ->withQueryString();
  }

  // =====

  public function forPublic($catId = null)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        $collection = $this->getResourcesforPublic('hotlines', Hotline::class, HotlineCategory::class, $catId);
        break;
      // prettier-ignore
      case "Manual":
        $collection = $this->getResourcesforPublic('manuals', Manual::class, ManualCategory::class, $catId);
        break;
      // prettier-ignore
      case "Reference":
        $collection = $this->getResourcesforPublic('references', Reference::class, ReferenceCategory::class, $catId);
        break;
      default:
        return [];
    }
    if (!$collection->count()) return []; // prettier-ignore
    return $collection->toArray();
  }

  private function getResourcesforPublic(
    $plural,
    $model,
    $catModel,
    $catId = null
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catId, function ($query) use ($catId) {
        $query->where("category_id", $catId);
      })
      ->whereIn("available_for", [0])
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->get();
    // ->paginate(self::OPTIONS["LIMIT_COUNT"])
    // ->withQueryString();
  }

  // =====

  public function forUserWithGroup($groupId, $catId = null)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        $collection = $this->getResourcesForUserWithGroup('hotlines', Hotline::class, HotlineCategory::class, $groupId, $catId);
        break;
      // prettier-ignore
      case "Manual":
        $collection = $this->getResourcesForUserWithGroup('manuals', Manual::class, ManualCategory::class, $groupId, $catId);
        break;
      // prettier-ignore
      case "Reference":
        $collection = $this->getResourcesForUserWithGroup('references', Reference::class, ReferenceCategory::class, $groupId, $catId);
        break;
      default:
        return [];
    }
    if (!$collection->count()) return []; // prettier-ignore
    return $collection->toArray();
  }

  private function getResourcesForUserWithGroup(
    $plural,
    $model,
    $catModel,
    $userGroupId,
    $catId = null
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catId, function ($query) use ($catId) {
        $query->where("category_id", $catId);
      })
      ->whereIn("available_for", [0, 2]) // This value never changes
      ->addSelect([
        "user_group_id" => User::select('user_group_id')->whereColumn("id",$plural.".user_id")->limit(1), // prettier-ignore
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->orWhere(function ($query) use ($catId, $userGroupId) {
        $query
          ->where("available_for", 1)
          ->whereHas("user", function ($groupQuery) use ($userGroupId) {
            $groupQuery->where("user_group_id", $userGroupId);
          })
          ->when($catId, function ($catQuery) use ($catId) {
            $catQuery->where("category_id", $catId);
          });
      })
      ->get();
    // ->paginate(self::OPTIONS["LIMIT_COUNT"])
    // ->withQueryString();
  }
  //
}
