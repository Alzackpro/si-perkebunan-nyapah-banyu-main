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
        Schema::table('pembibitan', function (Blueprint $table) {
            $table->integer('usia_bibit')->default(0)->after('tanggal_pembibitan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembibitan', function (Blueprint $table) {
            $table->dropColumn('usia_bibit');
        });
    }
};
