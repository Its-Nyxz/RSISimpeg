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
            $table->string('nama')->unique(); // misal: bpjs_tk, ppni, idi
            $table->string('slug')->unique();
            $table->enum('jenis', ['persentase', 'nominal'])->default('nominal');
            $table->boolean('is_wajib')->default(false); // untuk BPJS
            $table->timestamps();
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
