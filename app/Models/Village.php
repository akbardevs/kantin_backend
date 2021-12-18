<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Village extends Model
{
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getNameAttribute($string)
    {
        return ucwords(strtolower($string));
    }
}
