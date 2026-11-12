<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    use HasFactory;

    protected $table = 'komoditas';
    protected $primaryKey = 'id_komoditas';
    public $timestamps = false;

    protected $fillable = [
        'nama_komoditas',
        'keterangan',
    ];
}
