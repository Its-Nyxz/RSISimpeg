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
        Schema::create('t_gapok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gol_id');
            $table->unsignedBigInteger('gapok_id');
            $table->timestamps(0);

            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('gol_id')->references('id')->on('master_golongan');
            $table->foreign('gapok_id')->references('id')->on('master_gapok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_gapok');
    }
};
