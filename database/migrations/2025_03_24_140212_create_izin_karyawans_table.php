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
        Schema::create('izin_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_izin_id')->nullable()->constrained('jenis_izins')->onDelete('cascade');
            $table->foreignId('status_izin_id')->nullable()->constrained('status_cutis')->onDelete('cascade');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('jumlah_hari')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('bukti_izin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_karyawans');
    }
};
