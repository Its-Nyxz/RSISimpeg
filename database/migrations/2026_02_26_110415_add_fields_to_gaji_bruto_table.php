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
            $table->decimal('prosentase_tukin')->nullable();
            $table->decimal('KPI')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_bruto', function (Blueprint $table) {
            $table->dropColumn(['nom_poskes','nom_lembur', 'level_jabatan', 'nom_pendapatan_rs', 'prosentase_tukin', 'KPI']);
        });
    }
};
