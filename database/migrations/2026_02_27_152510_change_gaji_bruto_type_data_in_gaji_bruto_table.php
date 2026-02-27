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
        Schema::table('gaji_bruto', function (Blueprint $table) {
            $table->bigInteger('nom_lembur')->nullable()->change();
            $table->bigInteger('nom_poskes')->nullable()->change();
            $table->bigInteger('level_jabatan')->nullable()->change();
            $table->bigInteger('nom_pendapatan_rs')->nullable()->change();
            $table->bigInteger('nom_tukin_diterima')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_bruto', function (Blueprint $table) {
            $table->dropColumn(['nom_poskes', 'nom_lembur', 'level_jabatan', 'nom_pendapatan_rs', 'nom_tukin_diterima']);
        });
    }
};
