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
        Schema::create('hasil_pengecekans', function (Blueprint $table) {
            $table->id('id_pengecekan');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_area');
            $table->unsignedBigInteger('id_pengguna');
            $table->string('aktivitas');
            $table->unsignedBigInteger('id_peralatan')->nullable();
            $table->unsignedBigInteger('id_perlengkapan')->nullable();
            $table->unsignedBigInteger('id_komoditas_bibit')->nullable();
            $table->integer('jumlah_bibit')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_pengecekans');
    }
};
