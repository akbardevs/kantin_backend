<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class City extends Model{

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function getNameAttribute($string)
    {
        return ucwords(strtolower($string));
    }
}