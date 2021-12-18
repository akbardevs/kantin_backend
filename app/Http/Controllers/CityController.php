<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

// use App\Services\ResourceHelpers\CategoryService;
use App\Models\City;
use App\Models\District;

class CityController extends Controller
{
  const OPTIONS = [
    "SELECT_COLUMNS" => ["id", "name", "province_id"],
    "SELECT_COLUMNS_CHILD" => ["id", "name", "city_id"],
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
    $data = City::select(self::OPTIONS["SELECT_COLUMNS"])->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])->get(); // prettier-ignore
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
    $data = City::select(self::OPTIONS["SELECT_COLUMNS"])->findOrFail($id);
    return $data;
  }

  public function districtsInCity(Request $request, $id)
  {
    $data = District::select(self::OPTIONS["SELECT_COLUMNS_CHILD"])->where("city_id", $id)->get(); // prettier-ignore
    return $data;
  }
}
