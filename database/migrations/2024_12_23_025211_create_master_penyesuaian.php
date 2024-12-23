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
        Schema::create('master_penyesuaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendidikan_awal')->nullable();
            $table->unsignedBigInteger('pendidikan_penyesuaian')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->timestamps(0);

            // $table->foreign('pendidikan_awal')->references('id')->on('master_pendidikan');
            // $table->foreign('pendidikan_penyesuaian')->references('id')->on('master_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_penyesuaian');
    }
};
