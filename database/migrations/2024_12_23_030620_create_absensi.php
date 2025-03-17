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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_absensis')->onDelete('cascade');
            $table->foreignId('status_absen_id')->nullable()->constrained('status_absens')->onDelete('cascade');
            $table->integer('present')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('late')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('deskripsi_in')->nullable();
            $table->text('deskripsi_out')->nullable();
            $table->text('deskripsi_lembur')->nullable();
            $table->boolean('is_lembur')->nullable();
            $table->boolean('approved_lembur')->nullable();
            $table->text('feedback')->nullable();
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
