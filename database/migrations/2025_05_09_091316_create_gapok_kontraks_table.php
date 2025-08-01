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
        Schema::create('gapok_kontraks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_jabatan_id')->constrained()->onDelete('cascade'); // relasi ke katjab
            $table->unsignedInteger('min_masa_kerja'); // dalam bulan
            $table->unsignedInteger('max_masa_kerja'); // dalam bulan
            $table->unsignedBigInteger('nominal'); // nominal gapok dalam rupiah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gapok_kontraks');
    }
};
