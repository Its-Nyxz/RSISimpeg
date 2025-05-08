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
        Schema::create('riwayat_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_jabatan_id')->constrained('kategori_jabatans')->onDelete('cascade');

            // Pembeda tunjangan, bisa 'jabatan', 'fungsi', 'umum'
            $table->enum('tunjangan', ['jabatan', 'fungsi', 'umum']);

            // Tanggal mulai dan selesai menjabat
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable(); // null berarti masih aktif

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_jabatans');
    }
};
