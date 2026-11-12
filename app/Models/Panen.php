<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panen';
    protected $primaryKey = 'id_panen';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Satisfy DDL foreign key constraints
            if (!$model->id_jadwal) {
                $jadwal = Jadwal::where('id_area', $model->id_area)
                    ->where('id_komoditas', $model->id_komoditas)
                    ->where('jenis_kegiatan', 'panen')
                    ->first();
                $model->id_jadwal = $jadwal ? $jadwal->id_jadwal : 1;
            }
            if (!$model->id_peralatan) {
                $model->id_peralatan = 1;
            }
            if (!$model->id_perlengkapan) {
                $model->id_perlengkapan = 1;
            }
            if (!$model->id_status) {
                $model->id_status = 2; // Default status
            }
        });
    }

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'id_jadwal',
        'id_area',
        'id_komoditas',
        'id_peralatan',
        'id_perlengkapan',
        'id_status',
        'tanggal',
        'gross_panen',
        'netto_panen',
        'keterangan',

        // Old fields compatibility
        'area_kebun_id',
        'tanggal_panen',
        'jadwal_panen',
        'jenis_tanaman',
        'jumlah_panen',
        'panen_brondol',
        'harga_jual',
        'total_pendapatan',
        'user_id',
    ];

    // area_kebun_id -> id_area
    public function getAreaKebunIdAttribute()
    {
        return $this->id_area;
    }
    public function setAreaKebunIdAttribute($value)
    {
        $this->attributes['id_area'] = $value;
    }

    // tanggal_panen -> tanggal
    public function getTanggalPanenAttribute()
    {
        return $this->tanggal;
    }
    public function setTanggalPanenAttribute($value)
    {
        $this->attributes['tanggal'] = $value;
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

    // harga_jual -> calculated based on crop & grade
    public function getHargaJualAttribute()
    {
        $jenis = $this->jenis_tanaman;
        $ket = strtolower($this->keterangan ?? '');
        if ($jenis === 'kopi') {
            if (str_contains($ket, 'premium') || str_contains($ket, 'grade a')) return 45000;
            if (str_contains($ket, 'validated') || str_contains($ket, 'grade b')) return 38000;
            return 20000;
        } elseif ($jenis === 'lada') {
            if (str_contains($ket, 'premium') || str_contains($ket, 'grade a')) return 120000;
            return 90000;
        } else { // sawit
            return 2600;
        }
    }
    public function setHargaJualAttribute($value)
    {
        // ignored or cached
    }

    // total_pendapatan -> calculated
    public function getTotalPendapatanAttribute()
    {
        return $this->netto_panen * $this->harga_jual;
    }
    public function setTotalPendapatanAttribute($value)
    {
        // ignored
    }

    public function setJumlahPanenAttribute($value)
    {
        // ignored
    }

    public function setJadwalPanenAttribute($value)
    {
        // ignored
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_komoditas');
    }

    // user fallback
    public function getUserAttribute()
    {
        $userIdMap = [1 => 3, 2 => 2, 3 => 4];
        $targetUserId = $userIdMap[$this->id_komoditas] ?? 2;
        return User::find($targetUserId) ?? User::first();
    }

    protected function updateKeteranganField($key, $value)
    {
        $current = $this->keterangan ?? '';
        $pattern = '/\b' . preg_quote($key, '/') . ':\s*[^\|]+(\s*\|\s*)?/';
        $clean = preg_replace($pattern, '', $current);
        $clean = trim($clean, ' |');
        
        if (!is_null($value)) {
            $current = ($clean ? $clean . ' | ' : '') . $key . ': ' . $value;
        } else {
            $current = $clean;
        }
        
        $this->attributes['keterangan'] = $current;
    }

    // panen_brondol -> keterangan (parsed)
    public function getPanenBrondolAttribute()
    {
        if (preg_match('/Brondol:\s*([\d\.]+)/', $this->keterangan ?? '', $matches)) {
            return (float) $matches[1];
        }
        return 0.00;
    }
    public function setPanenBrondolAttribute($value)
    {
        $this->updateKeteranganField('Brondol', $value);
    }

    // Accessor untuk badge jenis tanaman
    public function getBadgeJenisTanamanAttribute()
    {
        $badge = [
            'kopi' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-mug-hot me-1"></i> Kopi</span>',
            'sawit' => '<span class="badge bg-success text-white"><i class="fa-solid fa-tree me-1"></i> Sawit</span>',
            'lada' => '<span class="badge bg-info text-dark"><i class="fa-solid fa-pepper-hot me-1"></i> Lada</span>',
        ];
        return $badge[$this->jenis_tanaman] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    // Relasi Many-to-One
    public function areaKebun()
    {
        return $this->belongsTo(AreaKebun::class, 'id_area', 'id_area');
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

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan', 'id_peralatan');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    public function status_panen()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    // id -> id_panen
    public function getIdAttribute()
    {
        return $this->id_panen;
    }

    // Ignore user_id as it doesn't exist in the database table
    public function setUserIdAttribute($value)
    {
        // ignored
    }

    // jumlah_panen_gross -> gross_panen
    public function getJumlahPanenGrossAttribute()
    {
        return $this->gross_panen;
    }
    public function setJumlahPanenGrossAttribute($value)
    {
        $this->attributes['gross_panen'] = $value;
    }

    // jumlah_netto -> netto_panen
    public function getJumlahNettoAttribute()
    {
        return $this->netto_panen;
    }
    public function setJumlahNettoAttribute($value)
    {
        $this->attributes['netto_panen'] = $value;
    }
}
