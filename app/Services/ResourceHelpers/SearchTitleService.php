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

class SearchTitleService extends Service
{
  const OPTIONS = [
    "LIMIT_COUNT" => 6,
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

  public function all($keyword)
  {
    switch ($this->resourceType) {
      case "Manual":
        return $this->getResourcesAll('manuals', Manual::class, ManualCategory::class, $keyword); // prettier-ignore
      case "Reference":
        return $this->getResourcesAll('references', Reference::class, ReferenceCategory::class, $keyword); // prettier-ignore
      default:
        return false;
    }
  }

  private function getResourcesAll($plural, $model, $catModel, $keyword)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      // ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("title", "like", "%{$keyword}%")
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      // ->get();
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->withQueryString();
  }

  // =====

  public function forPublic($keyword)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Manual":
        return $this->getResourcesForPublic('manuals', Manual::class, ManualCategory::class, $keyword);
      // prettier-ignore
      case "Reference":
        return $this->getResourcesForPublic('references', Reference::class, ReferenceCategory::class, $keyword);
      default:
        return false;
    }
  }

  private function getResourcesForPublic($plural, $model, $catModel, $keyword)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      // ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("title", "like", "%{$keyword}%")
      ->whereIn("available_for", [0])
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      // ->get();
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->withQueryString();
  }

  // =====

  public function forUserWithGroup($groupId, $keyword)
  {
    switch ($this->resourceType) {
      // prettier-ignore
      case "Manual":
        return $this->getResourcesForUserWithGroup('manuals', Manual::class, ManualCategory::class, $groupId, $keyword);
      // prettier-ignore
      case "Reference":
        return $this->getResourcesForUserWithGroup('references', Reference::class, ReferenceCategory::class, $groupId, $keyword);
      default:
        return false;
    }
  }

  private function getResourcesForUserWithGroup(
    $plural,
    $model,
    $catModel,
    $userGroupId,
    $keyword
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      // ->limit(self::OPTIONS["LIMIT_COUNT"])
      ->where("title", "like", "%{$keyword}%")
      ->whereIn("available_for", [0, 2]) // This value never changes
      ->addSelect([
        "user_group_id" => User::select('user_group_id')->whereColumn("id",$plural.".user_id")->limit(1), // prettier-ignore
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->orWhere(function ($query) use ($keyword, $userGroupId) {
        $query
          ->where("available_for", 1)
          ->whereHas("user", function ($groupQuery) use ($userGroupId) {
            $groupQuery->where("user_group_id", $userGroupId);
          })
          ->where("title", "like", "%{$keyword}%");
      })
      // ->get();
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->withQueryString();
  }
  //
}
