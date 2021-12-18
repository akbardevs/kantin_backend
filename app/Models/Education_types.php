<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education_types extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function education()
    {
        return $this->hasMany(Educations::class, 'type_id')->orderBy('id', 'desc');
    }
}
