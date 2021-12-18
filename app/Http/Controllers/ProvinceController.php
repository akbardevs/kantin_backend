<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;;
use App\Models\Province;
use App\Models\City;
use GuzzleHttp\Handler\StreamHandler as HandlerStreamHandler;
use Illuminate\Support\Facades\File as FacadesFile;

class ProvinceController extends Controller
{
  const OPTIONS = ["SELECT_COLUMNS" => ["id", "name"]];

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
    $data = Province::select(self::OPTIONS["SELECT_COLUMNS"])->orderBy(self::ORDER_BY[0], self::ORDER_BY[1])->get(); // prettier-ignore
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
    $data = Province::select(self::OPTIONS["SELECT_COLUMNS"])->findOrFail($id);
    return $data;
  }

  public function citiesInProvince(Request $request, $id)
  {
    $data = City::select(self::OPTIONS["SELECT_COLUMNS"])->where("province_id", $id)->get(); // prettier-ignore
    return $data;
  }
}
