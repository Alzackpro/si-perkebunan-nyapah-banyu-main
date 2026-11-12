<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanam extends Model
{
    use HasFactory;

    protected $table = 'tanam';
    protected $primaryKey = 'id_tanam';

    protected $fillable = [
        'id_area',
        'id_peralatan',
        'id_perlengkapan',
        'tanggal_tanam',
        'jumlah_tanam',
        'keterangan'
    ];

    public function area()
    {
        return $this->belongsTo(AreaKebun::class, 'id_area');
    }
}
