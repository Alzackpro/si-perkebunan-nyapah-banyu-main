<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->total_penjualan) {
                $model->total_penjualan = $model->jumlah_dikirim * $model->harga_jual;
            }
        });
    }

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'id_panen',
        'id_jadwal',
        'id_komoditas',
        'id_status',
        'tanggal_pengiriman',
        'jumlah_dikirim',
        'harga_jual',
        'total_penjualan',
        'keterangan',
    ];

    public function panen()
    {
        return $this->belongsTo(Panen::class, 'id_panen', 'id_panen');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas', 'id_komoditas');
    }

    public function status_pengiriman()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
}
