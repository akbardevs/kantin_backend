<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Educations extends Model
{
    use HasFactory;

    public function types()
    {
        return $this->belongsTo(Education_types::class, 'type_id');
    }
}
