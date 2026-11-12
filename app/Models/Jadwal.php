<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_komoditas',
        'id_area',
        'jenis_kegiatan',
        'frekuensi',
        'keterangan',
    ];

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas');
    }

    public function area()
    {
        return $this->belongsTo(AreaKebun::class, 'id_area');
    }
}
