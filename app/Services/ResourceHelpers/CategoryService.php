<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;
use Illuminate\Database\Eloquent\Model;

// Eloquent models
use App\Models\Hotline;
use App\Models\HotlineCategory;
use App\Models\ManualCategory;
use App\Models\ReferenceCategory;

class CategoryService extends Service
{
  const OPTIONS = [
    "SELECT_COLUMNS" => ["id", "parent_id", "name", "sort"],
  ];

  const ORDER_BY = ["sort", "ASC"]; // "ASC" in uppercase bcs we use orderByRaw.

  public function __construct()
  {
    $this->orderByString = "ISNULL(" . self::ORDER_BY[0] . "), " . self::ORDER_BY[0] . " " . self::ORDER_BY[1]; // prettier-ignore
  }

  public function execute()
  {
  }

  public function foo()
  {
    return "hello world"; // check check
  }

  // = = =

  /**
   * @return array
   */
  public function getWithChildrenById($resourceType, $id)
  {
    switch ($resourceType) {
      case "Manual":
        return $this->getModelWithChildrenById(ManualCategory::class, $id);
      case "Reference":
        return $this->getModelWithChildrenById(ReferenceCategory::class, $id);
      default:
        return [];
    }
  }

  private function getModelWithChildrenById($model, $id)
  {
    return $model
      ::where("parent_id", $id)
      ->pluck("id")
      ->push((int) $id)
      ->all();
  }

  // = = =

  public function getParentsWithChild($resourceType)
  {
    switch ($resourceType) {
      case "Hotline":
        $cats = $this->getModelParentsWithChild(HotlineCategory::class);
        break;
      case "Manual":
        $cats = $this->getModelParentsWithChild(ManualCategory::class);
        break;
      case "Reference":
        $cats = $this->getModelParentsWithChild(ReferenceCategory::class);
        break;
      default:
        return [];
    }
    return $cats->toArray();
  }

  public function getModelParentsWithChild($model, $extraRelation = null)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderByRaw($this->orderByString)
      ->where("parent_id", null)
      ->with("children:id,parent_id,name,sort")
      ->when($extraRelation, function ($query) use ($extraRelation) {
        $query->with($extraRelation);
      })
      ->get();
  }

  // = = =

  public function getParentsOnly($resourceType)
  {
    switch ($resourceType) {
      case "Hotline":
        $cats = $this->getModelParentsOnly(HotlineCategory::class);
        break;
      case "Manual":
        $cats = $this->getModelParentsOnly(ManualCategory::class);
        break;
      case "Reference":
        $cats = $this->getModelParentsOnly(ReferenceCategory::class);
        break;
      default:
        return [];
    }
    return $cats->toArray();
  }

  public function getModelParentsOnly($model)
  {
    return $model
      ::select(self::OPTIONS["SELECT_COLUMNS"])
      ->orderByRaw($this->orderByString)
      ->where("parent_id", null)
      ->get();
  }

  // = = =

  public function getById($resourceType, $id)
  {
    switch ($resourceType) {
      case "Hotline":
        return HotlineCategory::select(["name", "id", "parent_id"])->findOrFail($id); // prettier-ignore
      case "Manual":
        return ManualCategory::select(["name", "id", "parent_id"])->findOrFail($id); // prettier-ignore
      case "Reference":
        return ReferenceCategory::select(["name", "id", "parent_id"])->findOrFail($id); // prettier-ignore
      default:
        return false;
    }
  }
}
