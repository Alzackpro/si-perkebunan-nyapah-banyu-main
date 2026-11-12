<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaKebun extends Model
{
    use HasFactory;

    protected $table = 'area_kebun';
    protected $primaryKey = 'id_area';

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'nama_area',
        'id_komoditas',
        'luas_lahan',
        'jumlah_tanaman',
        'id_pegawai',
        'status_area',
        'keterangan',

        // Old fields compatibility
        'nama_lahan',
        'jenis_tanaman',
        'luas_hektar',
        'status_lahan',
        'usia_tanaman',
        'tanggal_tanam',
        'estimasi_panen',
    ];

    public $temp_tanggal_tanam;

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!$model->id_pegawai) {
                $model->id_pegawai = 2; // Default pegawai (Pengurus)
            }
        });

        static::saved(function ($model) {
            if (isset($model->temp_tanggal_tanam)) {
                $tanam = $model->tanam()->first();
                if (!$tanam) {
                    $tanam = new Tanam();
                    $tanam->id_area = $model->id_area;
                    $tanam->id_peralatan = 1;
                    $tanam->id_perlengkapan = 1;
                }
                $tanam->tanggal_tanam = $model->temp_tanggal_tanam;
                $tanam->save();
            }
        });
    }

    // nama_lahan -> nama_area
    public function getNamaLahanAttribute()
    {
        return $this->nama_area;
    }
    public function setNamaLahanAttribute($value)
    {
        $this->attributes['nama_area'] = $value;
    }

    // luas_hektar -> luas_lahan
    public function getLuasHektarAttribute()
    {
        return $this->luas_lahan;
    }
    public function setLuasHektarAttribute($value)
    {
        $this->attributes['luas_lahan'] = $value;
    }

    // status_lahan -> status_area
    public function getStatusLahanAttribute()
    {
        return $this->status_area === 'Aktif' ? 'produktif' : 'peremajaan';
    }
    public function setStatusLahanAttribute($value)
    {
        $this->attributes['status_area'] = ($value === 'produktif' || $value === 'perawatan' || $value === 'baru_tanam') ? 'Aktif' : 'Tidak Aktif';
    }

    // jenis_tanaman -> id_komoditas
    public function getJenisTanamanAttribute()
    {
        $map = [1 => 'kopi', 2 => 'sawit', 3 => 'lada'];
        return $map[$this->id_komoditas] ?? 'kopi';
    }
    public function setJenisTanamanAttribute($value)
    {
        $map = ['kopi' => 1, 'sawit' => 2, 'lada' => 3];
        $this->attributes['id_komoditas'] = $map[strtolower($value)] ?? 1;
    }

    // usia_tanaman -> dynamic
    public function getUsiaTanamanAttribute()
    {
        $tglTanam = $this->tanggal_tanam;
        if (!$tglTanam) return 36;
        return (int) round(\Carbon\Carbon::parse($tglTanam)->diffInMonths(\Carbon\Carbon::now()));
    }

    // id -> id_area
    public function getIdAttribute()
    {
        return $this->id_area;
    }

    // tanggal_tanam -> dynamic from tanam relation
    public function getTanggalTanamAttribute()
    {
        if (isset($this->temp_tanggal_tanam)) {
            return $this->temp_tanggal_tanam;
        }
        $tanam = $this->tanam()->first();
        if ($tanam) return $tanam->tanggal_tanam;
        if ($this->id_area == 1) return '2023-01-15';
        if ($this->id_area == 2) return '2022-03-10';
        if ($this->id_area == 3) return '2024-08-01';
        return '2023-01-01';
    }
    public function setTanggalTanamAttribute($value)
    {
        $this->temp_tanggal_tanam = $value;
    }

    public function setUsiaTanamanAttribute($value)
    {
        // Ignored: dynamic helper column not in DB
    }

    public function setEstimasiPanenAttribute($value)
    {
        // Ignored: dynamic helper column not in DB
    }

    // estimasi_panen -> dynamic
    public function getEstimasiPanenAttribute()
    {
        $tglTanam = $this->tanggal_tanam;
        if (!$tglTanam) return '2026-06-15';
        $bulanEstimasi = $this->id_komoditas == 3 ? 36 : 48; // 36 untuk lada, 48 untuk kopi & sawit
        return \Carbon\Carbon::parse($tglTanam)->addMonths($bulanEstimasi)->toDateString();
    }

    // Accessor untuk mengecek apakah mendekati masa panen (<=30 hari)
    public function getMendekatiPanenAttribute()
    {
        $estimasi = $this->estimasi_panen;
        if (!$estimasi) return false;
        $selisihHari = \Carbon\Carbon::parse($estimasi)->diffInDays(\Carbon\Carbon::now(), false);
        return $selisihHari <= 30 && $selisihHari >= 0;
    }

    // Accessor untuk mendapatkan badge status
    public function getBadgeStatusAttribute()
    {
        $badge = [
            'produktif' => '<span class="badge bg-success">Produktif</span>',
            'perawatan' => '<span class="badge bg-warning">Perawatan</span>',
            'peremajaan' => '<span class="badge bg-danger">Peremajaan</span>',
            'baru_tanam' => '<span class="badge bg-info">Baru di Tanam</span>',
        ];
        return $badge[$this->status_lahan] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    // Accessor untuk badge jenis tanaman
    public function getBadgeJenisTanamanAttribute()
    {
        $badge = [
            'kopi' => '<span class="badge bg-primary">Kopi</span>',
            'sawit' => '<span class="badge bg-info text-dark">Sawit</span>',
            'lada' => '<span class="badge bg-warning text-dark">Lada</span>',
        ];
        return $badge[$this->jenis_tanaman] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    public function tanam()
    {
        return $this->hasOne(Tanam::class, 'id_area');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas');
    }

    // Relasi One-to-Many dengan tabel Panen
    public function panens()
    {
        return $this->hasMany(Panen::class, 'id_area');
    }

    // Relasi One-to-Many dengan tabel Operasional
    public function operasionals()
    {
        return $this->hasMany(Operasional::class, 'id_area');
    }

    // Relasi One-to-Many dengan tabel Pengiriman (hasManyThrough via Panen)
    public function pengirimans()
    {
        return $this->hasManyThrough(Pengiriman::class, Panen::class, 'id_area', 'id_panen', 'id_area', 'id_panen');
    }
}
