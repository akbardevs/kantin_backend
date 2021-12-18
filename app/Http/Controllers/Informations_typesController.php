<?php

namespace App\Http\Controllers;

use App\Models\Information_types;
use Illuminate\Http\Request;

class Informations_typesController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = Information_types::get(); // prettier-ignore
        return $data;
    }
    
}
