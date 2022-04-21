<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'list_produk',
        'nama',
        'id_kasir',
        'nohp',
        'metode',
        'total',
    ];

    protected $dates = [
        'created_at',
    ];

    public function kasir()
    {
        return $this->belongsTo(Kasirs::class, 'id_kasir');
    }

    public function getCreatedAtAttribute($string)
    {
        if ($string != null) {
            $time = Carbon::parse($string)->toIso8601String();
            // Log::critical(Carbon::parse($time)->diffFordiffInHoursHumans());
            return Carbon::parse($time)->diffForHumans();
        }
    }
}
