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
        Schema::create('jenis_cutis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cuti')->nullable(); // Nama cuti (misalnya: Cuti Tahunan, Cuti Melahirkan, dll)
            $table->integer('durasi_default')->nullable(); // Durasi default (dalam hari)
            $table->boolean('dibayar')->default(true); // Apakah cuti ini dibayar atau tidak
            $table->boolean('hanya_untuk_karyawan_tetap')->default(false); // Apakah hanya untuk karyawan tetap
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_cutis');
    }
};
