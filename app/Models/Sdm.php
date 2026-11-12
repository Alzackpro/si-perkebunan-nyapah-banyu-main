<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sdm extends Model
{
    use HasFactory;

    protected $table = 'operasional_sdm';
    protected $primaryKey = 'id_upah';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->total_upah) {
                $model->total_upah = $model->jumlah_hari * $model->tarif_harian;
            }
        });
    }

    public function area()
    {
        return $this->belongsTo(AreaKebun::class, 'id_area', 'id_area');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas', 'id_komoditas');
    }

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'id_jadwal',
        'id_area',
        'id_komoditas',
        'nama_pekerja',
        'jenis_pekerjaan',
        'jumlah_hari',
        'tarif_harian',
        'total_upah',
        'tanggal_bayar',
        'bukti_pembayaran',
        'keterangan',

        // Old fields compatibility
        'upah_harian',
        'tanggal_kerja',
        'total_gaji',
    ];

    // upah_harian -> tarif_harian
    public function getUpahHarianAttribute()
    {
        return $this->tarif_harian;
    }
    public function setUpahHarianAttribute($value)
    {
        $this->attributes['tarif_harian'] = $value;
    }

    // tanggal_kerja -> tanggal_bayar
    public function getTanggalKerjaAttribute()
    {
        return $this->tanggal_bayar;
    }
    public function setTanggalKerjaAttribute($value)
    {
        $this->attributes['tanggal_bayar'] = $value;
    }

    // total_gaji -> total_upah
    public function getTotalGajiAttribute()
    {
        return $this->total_upah;
    }
    public function setTotalGajiAttribute($value)
    {
        $this->attributes['total_upah'] = $value;
    }

    // id -> id_upah
    public function getIdAttribute()
    {
        return $this->id_upah;
    }
}
