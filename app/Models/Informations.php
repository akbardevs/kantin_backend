<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informations extends Model
{
    use HasFactory;
    protected $fillable = [
        'information',
        'informant_id',
        'contact',
        'title',
        'from',
        'contact',
        'address',
        'type_id',
        'whatsapp',
        'phone',
        'status',
        'city_id',
    ];

    public function informants()
    {
        return $this->belongsTo(Informants::class,'informant_id');
    }

    public function tipe()
    {
        return $this->belongsTo(Information_types::class,'type_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
