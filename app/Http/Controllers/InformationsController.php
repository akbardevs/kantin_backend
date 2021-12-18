<?php

namespace App\Http\Controllers;

use App\Models\Informants;
use App\Models\Information_types;
use App\Models\Informations;
use App\Models\Province;
use Illuminate\Http\Request;

class InformationsController extends Controller
{
    //
    public function index(Request $request)
    {
        $category=[];
        $area=[];
        $data = Informations::with(['tipe','city.province'])->where('status',1)->get(); // prettier-ignore
        $prov = Province::all();
        $type = Information_types::orderBy('sort','asc')->get();
        foreach ($data as $get){ 
                
        }
        // $area = $data->groupBy('city.province');
        $valArr=
        [
            "info"=>$data,
            "wilayah"=>$prov,
            "kategori"=>$type,
        ];
        return $valArr;
    }

    public function filter(Request $request)
    {
        $data = Informations::get(); // prettier-ignore
        return $request->input("start");
    }

    public function store(Request $request){
        $saveInformant = Informants::create($request['informant']);
        if($saveInformant)$request['informant_id'] = $saveInformant['id'];
        $save = Informations::create($request->all());
        return $save;
    }
}
