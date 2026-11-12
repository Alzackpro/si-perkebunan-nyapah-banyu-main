<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $table = 'peralatan';
    protected $primaryKey = 'id_peralatan';
    public $timestamps = false;

    protected $fillable = [
        'nama_peralatan',
        'keterangan',
    ];
}
