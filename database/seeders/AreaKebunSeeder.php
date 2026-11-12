<?php

namespace Database\Seeders;

use App\Models\AreaKebun;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AreaKebunSeeder extends Seeder
{
    public function run(): void
    {
        $dummyData = [
            [
                'nama_lahan' => 'Kebun Kopi Blok A1',
                'jenis_tanaman' => 'kopi',
                'luas_hektar' => 5.5,
                'jumlah_tanaman' => 2200,
                'usia_tanaman' => 30,
                'tanggal_tanam' => Carbon::now()->subMonths(30)->format('Y-m-d'),
                'estimasi_panen' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'status_lahan' => 'produktif',
                'keterangan' => 'Lahan kopi utama dengan varietas Robusta',
            ],
            [
                'nama_lahan' => 'Kebun Sawit Blok B2',
                'jenis_tanaman' => 'sawit',
                'luas_hektar' => 10.0,
                'jumlah_tanaman' => 1400,
                'usia_tanaman' => 48,
                'tanggal_tanam' => Carbon::now()->subMonths(48)->format('Y-m-d'),
                'estimasi_panen' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'status_lahan' => 'produktif',
                'keterangan' => 'Lahan sawit dengan produktivitas tinggi',
            ],
            [
                'nama_lahan' => 'Kebun Lada Blok C3',
                'jenis_tanaman' => 'lada',
                'luas_hektar' => 3.2,
                'jumlah_tanaman' => 1600,
                'usia_tanaman' => 18,
                'tanggal_tanam' => Carbon::now()->subMonths(18)->format('Y-m-d'),
                'estimasi_panen' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'status_lahan' => 'produktif',
                'keterangan' => 'Lahan lada varietas Lampung',
            ],
            [
                'nama_lahan' => 'Kebun Kopi Blok D4',
                'jenis_tanaman' => 'kopi',
                'luas_hektar' => 4.8,
                'jumlah_tanaman' => 1920,
                'usia_tanaman' => 12,
                'tanggal_tanam' => Carbon::now()->subMonths(12)->format('Y-m-d'),
                'estimasi_panen' => Carbon::now()->addMonths(24)->format('Y-m-d'),
                'status_lahan' => 'perawatan',
                'keterangan' => 'Lahan kopi baru, masih dalam perawatan intensif',
            ],
            [
                'nama_lahan' => 'Kebun Sawit Blok E5',
                'jenis_tanaman' => 'sawit',
                'luas_hektar' => 8.5,
                'jumlah_tanaman' => 1190,
                'usia_tanaman' => 60,
                'tanggal_tanam' => Carbon::now()->subMonths(60)->format('Y-m-d'),
                'estimasi_panen' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'status_lahan' => 'replant',
                'keterangan' => 'Lahan sawit yang akan diremajakan tahun depan',
            ],
        ];

        foreach ($dummyData as $data) {
            AreaKebun::create($data);
        }
    }
}
