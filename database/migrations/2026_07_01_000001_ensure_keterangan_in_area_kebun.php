<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('area_kebun', 'keterangan')) {
            Schema::table('area_kebun', function (Blueprint $table) {
                $table->text('keterangan')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('area_kebun', 'keterangan')) {
            Schema::table('area_kebun', function (Blueprint $table) {
                $table->dropColumn('keterangan');
            });
        }
    }
};
