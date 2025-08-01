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
        Schema::create('master_jatah_cutis', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            // $table->unsignedBigInteger('golongan_id')->nullable(); // Atau jabatan_id
            $table->integer('jumlah_cuti')->default(12); // Default jatah 12 hari
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jatah_cutis');
    }
};
