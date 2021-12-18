<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Survivors extends Model
{
    use HasFactory;
    // public function getCreatedAtAttribute($string)
    // {
    //     $res = Carbon::parse($string)->diffForHumans();
    //     return $res;
    // }

    // public function getUpdatedAtAttribute($string)
    // {
    //     $res = Carbon::parse($string)->diffForHumans();
    //     return $res;
    // }
}
