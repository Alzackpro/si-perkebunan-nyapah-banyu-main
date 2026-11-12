<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('jenis_tanaman')->nullable()->after('area_kebun_id');
            $table->string('jenis_operasional')->nullable()->after('jenis_tanaman');
            $table->string('nama_pupuk')->nullable()->after('jadwal_operasional');
            $table->string('nama_obat_hama')->nullable()->after('nama_pupuk');
            $table->string('jenis_bibit')->nullable()->after('nama_obat_hama');
            $table->integer('jumlah_bibit')->nullable()->default(0)->after('jenis_bibit');
            $table->integer('jumlah_tanaman_mati')->nullable()->default(0)->after('jumlah_bibit');
            $table->integer('jumlah_tanaman_diganti')->nullable()->default(0)->after('jumlah_tanaman_mati');
            $table->string('status_operasional')->nullable()->default('Jadwal')->after('jumlah_tanaman_diganti');
            $table->text('keterangan')->nullable()->after('status_operasional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operasional', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_tanaman',
                'jenis_operasional',
                'nama_pupuk',
                'nama_obat_hama',
                'jenis_bibit',
                'jumlah_bibit',
                'jumlah_tanaman_mati',
                'jumlah_tanaman_diganti',
                'status_operasional',
                'keterangan',
            ]);
        });
    }
};
