<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembibitan extends Model
{
    use HasFactory;

    protected $table = 'pembibitan';
    protected $primaryKey = 'id_pembibitan';

    protected $fillable = [
        'id_jadwal',
        'id_komoditas',
        'id_pegawai',
        'id_peralatan',
        'id_perlengkapan',
        'tanggal_pembibitan',
        'usia_bibit',
        'jumlah_bibit',
        'jumlah_penyulaman',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan');
    }
}
