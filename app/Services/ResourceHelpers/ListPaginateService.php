<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;
use App\Services\ResourceHelpers\CategoryService;

// Eloquent models
use App\Models\Hotline;
use App\Models\HotlineCategory;
use App\Models\Manual;
use App\Models\ManualCategory;
use App\Models\Reference;
use App\Models\ReferenceCategory;
use App\Models\User;

class ListPaginateService extends Service
{
  const OPTIONS = [
    "LIMIT_COUNT" => 6,
    "SELECT_COLUMNS" => ["id", "title", "category_id", "created_at", "main_image", "available_for", "user_id"], // prettier-ignore
  ];

  const ORDER_BY = ["created_at", "desc"];

  public function __construct($resourceType)
  {
    $this->resourceType = $resourceType;
    $this->categorySvc = new CategoryService();
  }

  public function execute()
  {
  }

  public function all($catId = null)
  {
    $catIds = $catId ? $this->categorySvc->getWithChildrenById($this->resourceType, $catId) : null; // prettier-ignore

    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        return $this->getResourcesAll('hotlines', Hotline::class, HotlineCategory::class, $catIds)->toArray();
      // prettier-ignore
      case "Manual":
        return $this->getResourcesAll('manuals', Manual::class, ManualCategory::class, $catIds)->toArray();
      // prettier-ignore
      case "Reference":
        return $this->getResourcesAll('references', Reference::class, ReferenceCategory::class, $catIds)->toArray();
      default:
        return [];
    }
  }

  private function getResourcesAll($plural, $model, $catModel, $catIds = null)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catIds, function ($query) use ($catIds) {
        $query->whereIn("category_id", $catIds);
      })
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->withQueryString();
  }

  // =====

  public function forPublic($catId = null)
  {
    $catIds = $catId ? $this->categorySvc->getWithChildrenById($this->resourceType, $catId) : null; // prettier-ignore

    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        return $this->getResourcesforPublic('hotlines', Hotline::class, HotlineCategory::class, $catIds)->toArray();
      // prettier-ignore
      case "Manual":
        return $this->getResourcesforPublic('manuals', Manual::class, ManualCategory::class, $catIds)->toArray();
      // prettier-ignore
      case "Reference":
        return $this->getResourcesforPublic('references', Reference::class, ReferenceCategory::class, $catIds)->toArray();
      default:
        return [];
    }
  }

  private function getResourcesforPublic(
    $plural,
    $model,
    $catModel,
    $catIds = null
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catIds, function ($query) use ($catIds) {
        $query->whereIn("category_id", $catIds);
      })
      ->whereIn("available_for", [0])
      ->addSelect([
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->onEachSide(1)
      ->withQueryString();
  }

  // =====

  public function forUserWithGroup($groupId, $catId = null)
  {
    $catIds = $catId ? $this->categorySvc->getWithChildrenById($this->resourceType, $catId) : null; // prettier-ignore

    switch ($this->resourceType) {
      // prettier-ignore
      case "Hotline":
        return $this->getResourcesForUserWithGroup('hotlines', Hotline::class, HotlineCategory::class, $groupId, $catIds)->toArray();
      // prettier-ignore
      case "Manual":
        return $this->getResourcesForUserWithGroup('manuals', Manual::class, ManualCategory::class, $groupId, $catIds)->toArray();
      // prettier-ignore
      case "Reference":
        return $this->getResourcesForUserWithGroup('references', Reference::class, ReferenceCategory::class, $groupId, $catIds)->toArray();
      default:
        return [];
    }
  }

  private function getResourcesForUserWithGroup(
    $plural,
    $model,
    $catModel,
    $userGroupId,
    $catIds = null
  ) {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
      ->when($catIds, function ($query) use ($catIds) {
        $query->whereIn("category_id", $catIds);
      })
      ->whereIn("available_for", [0, 2]) // This value never changes
      ->addSelect([
        "user_group_id" => User::select('user_group_id')->whereColumn("id",$plural.".user_id")->limit(1), // prettier-ignore
        "category_name" => $catModel::select("name")->whereColumn("id",$plural.".category_id")->limit(1), // prettier-ignore
      ])
      ->orWhere(function ($query) use ($catIds, $userGroupId) {
        $query
          ->where("available_for", 1)
          ->whereHas("user", function ($groupQuery) use ($userGroupId) {
            $groupQuery->where("user_group_id", $userGroupId);
          })
          ->when($catIds, function ($catQuery) use ($catIds) {
            $catQuery->whereIn("category_id", $catIds);
          });
      })
      ->paginate(self::OPTIONS["LIMIT_COUNT"])
      ->withQueryString();
  }
  //
}
