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
            $table->integer('nom_poskes')->nullable();
            $table->integer('nom_lembur')->nullable();
            $table->integer('level_jabatan')->nullable();
            $table->integer('nom_pendapatan_rs')->nullable();
            // Precision 8, Scale 4 berarti total 8 digit, dengan 4 digit di belakang koma (contoh: 1234.5678)
            $table->decimal('prosentase_tukin', 8, 4)->nullable();

            // KPI: 95,3 (biasanya 1 atau 2 angka di belakang koma)
            // Precision 5, Scale 2 (contoh: 100.00 atau 95.30)
            $table->decimal('KPI', 5, 1)->nullable();
            $table->integer('nom_tukin_diterima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_bruto', function (Blueprint $table) {
            $table->dropColumn(['nom_poskes', 'nom_lembur', 'level_jabatan', 'nom_pendapatan_rs', 'prosentase_tukin', 'KPI', 'nom_tukin_diterima']);
        });
    }
};
