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
            Schema::create('point_kinerjas', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->decimal('batas_bawah');
                $table->decimal('batas_atas');
                $table->decimal('point');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_kinerjas');
    }
};
