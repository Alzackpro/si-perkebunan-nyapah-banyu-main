<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_user',
        'nama_pegawai',
        'jabatan',
        'no_tlp',
        'alamat',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
