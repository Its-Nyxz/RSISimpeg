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
        Schema::create('master_potongan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('katjab_id')->nullable();
            $table->string('nama')->nullable();
            $table->integer('nominal')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps(0);

            $table->foreign('katjab_id')->references('id')->on('kategori_jabatans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_potongan');
    }
};
