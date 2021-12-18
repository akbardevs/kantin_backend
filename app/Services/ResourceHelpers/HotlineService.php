<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;
use App\Services\ResourceHelpers\CategoryService;

use App\Models\Hotline;
use App\Models\HotlineCategory;

class HotlineService extends Service
{
  const OPTIONS = [
    "SELECT_COLUMNS" => ["id", "title", "category_id", "phone", "email", "location", "district_id"], // prettier-ignore
  ];

  const ORDER_BY = ["sort", "asc"];

  public function __construct()
  {
  }

  public function execute()
  {
  }

  public function get($params = [])
  {
    if (array_key_exists("keyword", $params) && $params["keyword"]) {
      return Hotline::select(self::OPTIONS["SELECT_COLUMNS"])
        ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
        ->where("title", "like", "%{$params["keyword"]}%")
        ->get();
		} 
		// prettier-ignore
		elseif (array_key_exists("districtId", $params) && $params["districtId"]) {
      return Hotline::select(self::OPTIONS["SELECT_COLUMNS"])
        ->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])
        ->where("district_id", $params["districtId"])
        ->get();
    }
    return Hotline::orderBy('sort','asc')->get();
	}
	
	public function addToCategories($hotlines = []) {
		$categorySvc = new CategoryService();

    $cats = $categorySvc->getModelParentsWithChild(HotlineCategory::class);
    $cats->transform(function ($cat, $i) use ($hotlines) {
      $cat["hotlines"] = $cat->getThisAndChildrenHotlines($hotlines);
      return $cat;
		});
		return $cats;
	}
}
