<?php

namespace App\Http\Controllers;

use App\Models\Education_types;
use App\Models\Educations;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = Education_types::with('education')->get(); // prettier-ignore
        return $data;
    }
}
