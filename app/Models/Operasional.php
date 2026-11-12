<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operasional extends Model
{
    use HasFactory;

    protected $table = 'perawatan';
    protected $primaryKey = 'id_perawatan';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Satisfy DDL foreign key constraints
            if (!$model->id_jadwal) {
                $jadwal = Jadwal::where('id_area', $model->id_area)
                    ->where('id_komoditas', $model->id_komoditas)
                    ->where('jenis_kegiatan', 'perawatan')
                    ->first();
                $model->id_jadwal = $jadwal ? $jadwal->id_jadwal : 4;
            }
            if (!$model->id_pegawai) {
                $user = auth()->user();
                $model->id_pegawai = $user && $user->pegawai ? $user->pegawai->id_pegawai : ($model->id_komoditas == 1 ? 3 : 2);
            }
            if (!$model->id_peralatan) {
                $model->id_peralatan = 1;
            }
            if (!$model->id_perlengkapan) {
                $model->id_perlengkapan = 1;
            }
        });
    }

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'id_jadwal',
        'id_area',
        'id_komoditas',
        'id_pegawai',
        'id_peralatan',
        'id_perlengkapan',
        'jenis_perawatan',
        'tanggal_perawatan',
        'keterangan',

        // Old fields compatibility
        'area_kebun_id',
        'jenis_tanaman',
        'jenis_operasional',
        'jadwal_operasional',
        'nama_pupuk',
        'nama_obat_hama',
        'jenis_bibit',
        'jumlah_bibit',
        'jumlah_tanaman_mati',
        'jumlah_tanaman_diganti',
        'status_operasional',
        'pupuk_digunakan',
        'obat_hama_digunakan',
        'bibit_baru',
        'pergantian_tanaman',
        'biaya_operasional',
        'bukti_nota',
        'user_id',
    ];

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

    // area_kebun_id -> id_area
    public function getAreaKebunIdAttribute()
    {
        return $this->id_area;
    }
    public function setAreaKebunIdAttribute($value)
    {
        $this->attributes['id_area'] = $value;
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

    // jenis_operasional -> jenis_perawatan
    public function getJenisOperasionalAttribute()
    {
        $val = $this->jenis_perawatan;
        if ($val === 'Obat Hama') return 'Pemberian Obat Hama';
        if ($val === 'Pemupukan dan Obat Hama') return 'Pergantian Tanaman';
        return $val ?? 'Pemupukan';
    }
    public function setJenisOperasionalAttribute($value)
    {
        if ($value === 'Pemberian Obat Hama') {
            $this->attributes['jenis_perawatan'] = 'Obat Hama';
        } elseif ($value === 'Pembibitan' || $value === 'Pergantian Tanaman') {
            $this->attributes['jenis_perawatan'] = 'Pemupukan dan Obat Hama';
        } else {
            $this->attributes['jenis_perawatan'] = 'Pemupukan';
        }
    }

    // jadwal_operasional -> tanggal_perawatan
    public function getJadwalOperasionalAttribute()
    {
        return $this->tanggal_perawatan;
    }
    public function setJadwalOperasionalAttribute($value)
    {
        $this->attributes['tanggal_perawatan'] = $value;
    }

    // biaya_operasional -> keterangan (parsed)
    public function getBiayaOperasionalAttribute()
    {
        if (preg_match('/Biaya:\s*([\d\.]+)/', $this->keterangan ?? '', $matches)) {
            return (float) $matches[1];
        }
        return 0.00;
    }
    public function setBiayaOperasionalAttribute($value)
    {
        $this->updateKeteranganField('Biaya', $value);
    }

    // bukti_nota -> keterangan (parsed)
    public function getBuktiNotaAttribute()
    {
        if (preg_match('/Nota:\s*([^\|]+)/', $this->keterangan ?? '', $matches)) {
            return trim($matches[1]);
        }
        return null;
    }
    public function setBuktiNotaAttribute($value)
    {
        $this->updateKeteranganField('Nota', $value);
    }

    // pupuk_digunakan -> keterangan (parsed)
    public function getPupukDigunakanAttribute()
    {
        if (preg_match('/Pupuk:\s*([\d\.]+)/', $this->keterangan ?? '', $matches)) {
            return (float) $matches[1];
        }
        return 0.00;
    }
    public function setPupukDigunakanAttribute($value)
    {
        $this->updateKeteranganField('Pupuk', $value);
    }

    // obat_hama_digunakan -> keterangan (parsed)
    public function getObatHamaDigunakanAttribute()
    {
        if (preg_match('/Obat:\s*([\d\.]+)/', $this->keterangan ?? '', $matches)) {
            return (float) $matches[1];
        }
        return 0.00;
    }
    public function setObatHamaDigunakanAttribute($value)
    {
        $this->updateKeteranganField('Obat', $value);
    }

    // pergantian_tanaman -> keterangan (parsed)
    public function getPergantianTanamanAttribute()
    {
        if (preg_match('/Sulam:\s*(\d+)/', $this->keterangan ?? '', $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }
    public function setPergantianTanamanAttribute($value)
    {
        $this->updateKeteranganField('Sulam', $value);
    }

    // bibit_baru -> keterangan (parsed)
    public function getBibitBaruAttribute()
    {
        if (preg_match('/Bibit:\s*(\d+)/', $this->keterangan ?? '', $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }
    public function setBibitBaruAttribute($value)
    {
        $this->updateKeteranganField('Bibit', $value);
    }

    // nama_pupuk -> keterangan
    public function getNamaPupukAttribute()
    {
        if (preg_match('/NamaPupuk:\s*([^\|]+)/', $this->keterangan ?? '', $matches)) {
            return trim($matches[1]);
        }
        return 'Standard';
    }
    public function setNamaPupukAttribute($value)
    {
        $this->updateKeteranganField('NamaPupuk', $value);
    }

    // nama_obat_hama -> keterangan
    public function getNamaObatHamaAttribute()
    {
        if (preg_match('/NamaObat:\s*([^\|]+)/', $this->keterangan ?? '', $matches)) {
            return trim($matches[1]);
        }
        return 'Standard';
    }
    public function setNamaObatHamaAttribute($value)
    {
        $this->updateKeteranganField('NamaObat', $value);
    }

    // status_operasional -> keterangan
    public function getStatusOperasionalAttribute()
    {
        if (preg_match('/Status:\s*([^\|]+)/', $this->keterangan ?? '', $matches)) {
            return trim($matches[1]);
        }
        return 'Selesai';
    }
    public function setStatusOperasionalAttribute($value)
    {
        $this->updateKeteranganField('Status', $value);
    }

    public function setJenisBibitAttribute($value)
    {
        // ignored
    }

    public function setJumlahBibitAttribute($value)
    {
        // ignored
    }

    public function setJumlahTanamanMatiAttribute($value)
    {
        // ignored
    }

    public function setJumlahTanamanDigantiAttribute($value)
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

    // Accessor untuk badge jenis operasional
    public function getBadgeJenisOperasionalAttribute()
    {
        $badge = [
            'Pemupukan' => '<span class="badge bg-primary"><i class="fa-solid fa-seedling me-1"></i> Pemupukan</span>',
            'Pemberian Obat Hama' => '<span class="badge bg-danger"><i class="fa-solid fa-bug-slash me-1"></i> Pemberian Obat Hama</span>',
            'Pembibitan' => '<span class="badge bg-success"><i class="fa-solid fa-leaf me-1"></i> Pembibitan</span>',
            'Pergantian Tanaman' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-arrows-rotate me-1"></i> Pergantian Tanaman</span>',
        ];
        return $badge[$this->jenis_operasional] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    // Accessor untuk badge status operasional
    public function getBadgeStatusAttribute()
    {
        $badge = [
            'Selesai' => '<span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Selesai</span>',
            'Jadwal' => '<span class="badge bg-info"><i class="fa-solid fa-clock me-1"></i> Jadwal</span>',
            'Proses' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner me-1"></i> Proses</span>',
        ];
        return $badge[$this->status_operasional] ?? '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }

    // Accessor untuk mengecek apakah jadwal mendekat (<=7 hari)
    public function getMendekatAttribute()
    {
        if (!$this->jadwal_operasional) return false;
        $selisihHari = \Carbon\Carbon::parse($this->jadwal_operasional)->diffInDays(\Carbon\Carbon::now(), false);
        return $selisihHari <= 7 && $selisihHari >= 0;
    }

    // Relasi Many-to-One dengan tabel AreaKebun
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

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan', 'id_peralatan');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    // id -> id_perawatan
    public function getIdAttribute()
    {
        return $this->id_perawatan;
    }

    // Map user_id to id_pegawai to prevent SQL errors and save the correct operator relation
    public function setUserIdAttribute($value)
    {
        $pegawai = Pegawai::where('id_user', $value)->first();
        if ($pegawai) {
            $this->attributes['id_pegawai'] = $pegawai->id_pegawai;
        }
    }

    public function getNamaPeralatanAttribute()
    {
        $ids = json_decode($this->attributes['id_peralatan'] ?? '[]', true);
        if (is_array($ids)) {
            $items = \App\Models\Peralatan::whereIn('id_peralatan', $ids)->pluck('nama_peralatan')->toArray();
            return empty($items) ? '-' : implode(', ', $items);
        }
        return optional($this->peralatan)->nama_peralatan ?? '-';
    }

    public function getNamaPerlengkapanAttribute()
    {
        $ids = json_decode($this->attributes['id_perlengkapan'] ?? '[]', true);
        if (is_array($ids)) {
            $items = \App\Models\Perlengkapan::whereIn('id_perlengkapan', $ids)->pluck('nama_perlengkapan')->toArray();
            return empty($items) ? '-' : implode(', ', $items);
        }
        return optional($this->perlengkapan)->nama_perlengkapan ?? '-';
    }

    public function getIdsPeralatanAttribute()
    {
        $ids = json_decode($this->attributes['id_peralatan'] ?? '[]', true);
        return is_array($ids) ? $ids : [$this->attributes['id_peralatan'] ?? 1];
    }

    public function getIdsPerlengkapanAttribute()
    {
        $ids = json_decode($this->attributes['id_perlengkapan'] ?? '[]', true);
        return is_array($ids) ? $ids : [$this->attributes['id_perlengkapan'] ?? 1];
    }
}
