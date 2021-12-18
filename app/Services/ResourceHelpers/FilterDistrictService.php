<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;

// Eloquent models
// use App\Models\Hotline;
use App\Models\Manual;
use App\Models\ManualCategory;
use App\Models\Reference;
use App\Models\ReferenceCategory;
use App\Models\User;

class FilterDistrictService extends Service
{
  const OPTIONS = [
    "LIMIT_COUNT" => 48,
    "SELECT_COLUMNS" => ["id", "title", "category_id", "created_at", "main_image", "available_for", "user_id"], // prettier-ignore
  ];

  const ORDER_BY = ["created_at", "desc"];

  function __construct($resourceType)
  {
    $this->resourceType = $resourceType;
  }

  public function execute()
  {
  }

  public function all($districtId)
  {
    switch ($this->resourceType) {
      case "Manual":
        return $this->getResourcesAll('manuals', Manual::class, ManualCategory::class, $districtId); // prettier-ignore
      case "Reference":
        return $this->getResourcesAll('references', Reference::class, ReferenceCategory::class, $districtId); // prettier-ignore
      default:
        return false;
    }
  }

  private function getResourcesAll($plural, $model, $catModel, $districtId)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("district_id", $districtId)
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->get();
  }

  // =====

  public function forPublic($districtId)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Manual":
        return $this->getResourcesforPublic('manuals', Manual::class, ManualCategory::class, $districtId);
      // prettier-ignore
      case "Reference":
        return $this->getResourcesforPublic('references', Reference::class, ReferenceCategory::class, $districtId);
      default:
        return false;
    }
  }

  private function getResourcesforPublic(
    $plural,
    $model,
    $catModel,
    $districtId
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("district_id", $districtId)
      ->whereIn("available_for", [0])
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->get();
  }

  // =====

  public function forUserWithGroup($groupId, $districtId)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Manual":
        return $this->getResourcesForUserWithGroup('manuals', Manual::class, ManualCategory::class, $groupId, $districtId);
      // prettier-ignore
      case "Reference":
        return $this->getResourcesForUserWithGroup('references', Reference::class, ReferenceCategory::class, $groupId, $districtId);
      default:
        return false;
    }
  }

  private function getResourcesForUserWithGroup(
    $plural,
    $model,
    $catModel,
    $userGroupId,
    $districtId
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("district_id", $districtId)
      ->whereIn("available_for", [0, 2]) // This value never changes
      ->addSelect([
        "user_group_id" => User::select('user_group_id')->whereColumn("id",$plural.".user_id")->limit(1), // prettier-ignore
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->orWhere(function ($query) use ($districtId, $userGroupId) {
        $query
          ->where("available_for", 1)
          ->whereHas("user", function ($groupQuery) use ($userGroupId) {
            $groupQuery->where("user_group_id", $userGroupId);
          })
          ->where("district_id", $districtId);
      })
      ->get();
  }
  //
}
