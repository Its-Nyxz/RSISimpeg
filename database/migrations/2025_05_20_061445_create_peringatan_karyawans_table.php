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
        Schema::create('peringatan_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('tingkat', ['I', 'II', 'III', 'IV']); // Tingkat pelanggaran
            $table->string('jenis_pelanggaran'); // Misalnya: 'Tidak hadir tanpa keterangan'
            $table->text('keterangan')->nullable(); // Penjelasan tambahan jika ada

            $table->date('tanggal_sp'); // Tanggal dikeluarkannya SP
            $table->integer('sanksi')->nullable();

            $table->boolean('is_toleransi')->default(true); // Apakah SP ini dihitung toleransi
            $table->boolean('is_phk')->default(false); // Apakah langsung menyebabkan PHK

            $table->string('file_sp')->nullable(); // Path ke file SP yang diupload (PDF/JPG)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peringatan_karyawans');
    }
};
