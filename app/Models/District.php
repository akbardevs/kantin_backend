<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class District extends Model{

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getNameAttribute($string)
    {
        return ucwords(strtolower($string));
    }
}