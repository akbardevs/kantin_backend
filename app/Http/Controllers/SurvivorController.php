<?php

namespace App\Http\Controllers;

use App\Models\Survivors;
use Illuminate\Http\Request;

class SurvivorController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = Survivors::orderBy('id', 'desc')->get(); // prettier-ignore
        return $data;
    }

    public function slug($slug)
    {
        $data = Survivors::where('slug', $slug)->get(); // prettier-ignore
        return $data;
    }
}
