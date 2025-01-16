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
        Schema::create('point_jabatans', function (Blueprint $table) {
            $table->id();
            $table->morphs('pointable'); // Menyimpan ID dan tipe model (umum atau fungsional)
            $table->integer('point'); // Menyimpan point
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_jabatans');
    }
};
