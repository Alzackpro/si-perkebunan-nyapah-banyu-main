<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'log_aktivitas';

    // Kolom-kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
    ];

    public function save(array $options = [])
    {
        \Illuminate\Support\Facades\Log::info("LogAktivitas: [User ID: {$this->user_id}] {$this->aktivitas} - {$this->deskripsi}");
        return parent::save($options);
    }

    // Relasi Many-to-One dengan tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
