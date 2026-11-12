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
        Schema::table('panen', function (Blueprint $table) {
            $table->string('jadwal_panen')->nullable()->after('tanggal_panen');
            $table->decimal('jumlah_panen', 15, 2)->nullable()->after('jadwal_panen');
            $table->decimal('gross_panen', 15, 2)->default(0)->after('jumlah_panen');
            $table->decimal('netto_panen', 15, 2)->default(0)->after('gross_panen');
            $table->decimal('panen_brondol', 15, 2)->nullable()->after('netto_panen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panen', function (Blueprint $table) {
            $table->dropColumn(['jadwal_panen', 'jumlah_panen', 'gross_panen', 'netto_panen', 'panen_brondol']);
        });
    }
};
