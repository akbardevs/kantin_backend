<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Province extends Model{

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function getNameAttribute($string)
    {
        return ucwords(strtolower($string));
    }
}