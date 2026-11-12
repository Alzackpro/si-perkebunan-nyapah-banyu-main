<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPengecekan extends Model
{
    protected $table = 'hasil_pengecekans';
    protected $primaryKey = 'id_pengecekan';

    protected $fillable = [
        'tanggal',
        'id_area',
        'id_pengguna',
        'aktivitas',
        'id_peralatan',
        'id_perlengkapan',
        'id_komoditas_bibit',
        'jumlah_bibit'
    ];

    public function areaKebun()
    {
        return $this->belongsTo(AreaKebun::class, 'id_area', 'id_area');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_user');
    }

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan', 'id_peralatan');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    public function komoditasBibit()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas_bibit', 'id_komoditas');
    }
}
