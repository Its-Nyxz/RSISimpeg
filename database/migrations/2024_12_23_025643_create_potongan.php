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
        Schema::create('potongan', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bruto_id')->nullable()->constrained('gaji_bruto')->onDelete('cascade');
            $table->foreignId('master_potongan_id')->constrained('master_potongan')->onDelete('cascade');
            $table->unsignedInteger('nominal')->default(0); // hasil akhir potongan bulan ini
            $table->unsignedTinyInteger('bulan_penggajian');
            $table->year('tahun_penggajian');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan');
    }
};
