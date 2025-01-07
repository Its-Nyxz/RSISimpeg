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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_absensis')->onDelete('cascade');
            $table->foreignId('opsi_id')->constrained('opsi_absens')->onDelete('cascade');
            $table->integer('present')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('late')->nullable();
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->enum('status_hadir', ['Hadir', 'Tidak Hadir', 'Terlambat'])->default('Hadir');
            $table->enum('keterangan_absen', ['Cuti', 'Libur', 'Tugas', 'Ijin', 'Sakit'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
