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
        Schema::create('nilaipphs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoripphs')->onDelete('cascade');
            $table->decimal('upper_limit', 15, 2)->nullable(); // Nilai batas atas
            $table->decimal('tax_rate', 10, 4); // Nilai tax rate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilaipphs');
    }
};
