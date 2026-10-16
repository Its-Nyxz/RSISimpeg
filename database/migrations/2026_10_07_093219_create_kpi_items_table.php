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
        Schema::create('kpi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_id')->constrained('kpi_penilaians')->cascadeOnDelete();
            $table->string('perspektif')->nullable(); // Finansial, Proses Produksi, Customer, Growth and Learning
            $table->string('sasaran_kinerja')->nullable();
            $table->text('parameter_kpi')->nullable();
            $table->decimal('bobot', 8, 2)->default(0);
            $table->decimal('target', 8, 2)->default(0);
            $table->decimal('realisasi', 8, 2)->default(0);
            $table->decimal('skor', 8, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_items');
    }
};
