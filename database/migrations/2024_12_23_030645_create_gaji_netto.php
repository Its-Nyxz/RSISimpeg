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
        Schema::create('gaji_netto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bruto_id')->constrained('gaji_bruto')->onDelete('cascade');
            $table->integer('total_netto')->nullable();
            $table->date('tanggal_transfer')->nullable();
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_netto');
    }
};
