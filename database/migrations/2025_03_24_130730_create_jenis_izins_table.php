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
        Schema::create('jenis_izins', function (Blueprint $table) {
            $table->id();
            $table->string('nama_izin')->nullable();
            $table->integer('durasi_default')->nullable();
            $table->boolean('dibayar')->default(true);
            $table->boolean('hanya_untuk_karyawan_tetap')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_izins');
    }
};
