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
        Schema::create('potongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bruto_id')->nullable()->constrained('gaji_bruto')->onDelete('cascade');
            $table->integer('simpanan_wajib')->nullable();
            $table->integer('simpanan_pokok')->nullable();
            $table->integer('ibi')->nullable();
            $table->integer('idi')->nullable();
            $table->integer('ppni')->nullable();
            $table->integer('pinjam_kop')->nullable();
            $table->integer('obat')->nullable();
            $table->integer('a_b')->nullable();
            $table->integer('a_p')->nullable();
            $table->integer('dansos')->nullable();
            $table->integer('dplk')->nullable();
            $table->integer('bpjs_tk')->nullable();
            $table->integer('bpjs_kes')->nullable();
            $table->integer('lain')->nullable();
            $table->timestamps(0);

            // $table->foreign('bruto_id')->references('id')->on('gaji_bruto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan');
    }
};
