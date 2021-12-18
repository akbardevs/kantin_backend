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
    public function kasir()
    {
        return $this->belongsTo(Kasirs::class, 'id_kasir');
    }
}
