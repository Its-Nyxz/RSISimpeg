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
        Schema::create('master_fungsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('katjab_id')->nullable()->constrained('kategori_jabatans')->onDelete('cascade');
            $table->integer('nominal')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_fungsi');
    }
};
