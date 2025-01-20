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
        Schema::create('masa_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('batas_bawah')->nullable();
            $table->decimal('batas_atas')->nullable();
            $table->decimal('point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masa_kerjas');
    }
};
