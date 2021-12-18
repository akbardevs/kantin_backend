<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

// use App\Services\ResourceHelpers\CategoryService;
use App\Models\District;
use App\Models\Village;

class DistrictController extends Controller
{
  const OPTIONS = [
    "SELECT_COLUMNS" => ["id", "name", "city_id"],
    "SELECT_COLUMNS_CHILD" => ["id", "name", "district_id"],
  ];

  const ORDER_BY = ["name", "asc"];

  public function __construct()
  {
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $data = District::select(self::OPTIONS["SELECT_COLUMNS"])->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])->get(); // prettier-ignore
    return $data;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $data = District::select(self::OPTIONS["SELECT_COLUMNS"])->findOrFail($id);
    return $data;
  }

  public function villages(Request $request, $id)
  {
    $data = Village::select(self::OPTIONS["SELECT_COLUMNS_CHILD"])->where("district_id", $id)->get(); // prettier-ignore
    return $data;
  }
}
