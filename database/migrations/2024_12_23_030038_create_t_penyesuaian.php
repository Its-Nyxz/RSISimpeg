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
        Schema::create('t_penyesuaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('gol_id');
            $table->unsignedBigInteger('penyesuaian_id');
            $table->date('tanggal_penyesuaian')->nullable();
            $table->string('status_penyesuaian')->nullable();
            $table->timestamps(0);

            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('gol_id')->references('id')->on('master_golongan');
            $table->foreign('penyesuaian_id')->references('id')->on('master_penyesuaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penyesuaian');
    }
};
