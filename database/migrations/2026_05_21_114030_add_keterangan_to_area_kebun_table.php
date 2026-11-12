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
        Schema::table('area_kebun', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('status_lahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area_kebun', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
