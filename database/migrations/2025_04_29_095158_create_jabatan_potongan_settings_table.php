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
        Schema::create('jabatan_potongan_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('katjab_id')->nullable()->constrained('kategori_jabatans')->onDelete('cascade');
            $table->foreignId('master_potongan_id')->nullable()->constrained('master_potongan')->onDelete('cascade');
            $table->tinyInteger('bulan')->nullable();
            $table->smallInteger('tahun')->nullable();
            $table->decimal('nilai', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_potongan_settings');
    }
};
