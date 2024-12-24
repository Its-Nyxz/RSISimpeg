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
        Schema::create('master_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            // $table->integer('minim_gol')->nullable();
            $table->foreignId('minim_gol')->nullable()->constrained('master_golongan');
            // $table->integer('maxim_gol')->nullable();
            $table->foreignId('maxim_gol')->nullable()->constrained('master_golongan');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pendidikan');
    }
};
