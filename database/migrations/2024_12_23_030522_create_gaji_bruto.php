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
        Schema::create('gaji_bruto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('bulan_penggajian');
            $table->integer('nom_jabatan')->nullable();
            $table->integer('nom_fungsi')->nullable();
            $table->integer('nom_umum')->nullable();
            $table->integer('nom_khusus')->nullable();
            $table->integer('nom_trans')->nullable();
            $table->integer('nom_pj_poskes')->nullable();
            $table->integer('nom_p_shift')->nullable();
            $table->integer('nom_lainnya')->nullable();
            $table->integer('total_bruto')->nullable();
            $table->timestamps(0);

            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('trans_id')->references('id')->on('master_trans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_bruto');
    }
};
