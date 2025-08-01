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
        Schema::create('gapok_kontrak_penyesuaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gapok_kontrak_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('tanggal_berlaku')->nullable(); // mulai efektif UMK baru
            $table->unsignedBigInteger('nominal_baru')->nullable(); // nominal setelah penyesuaian
            $table->string('keterangan')->nullable(); // opsional: catatan alasan penyesuaian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gapok_kontrak_penyesuaians');
    }
};
