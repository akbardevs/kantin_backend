<?php

namespace App\Http\Controllers;

use App\Models\Kasirs;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    //
    public function list()
    {
        return Kasirs::all();
    }
}
