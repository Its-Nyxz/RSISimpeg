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
            // $table->unsignedBigInteger('employee_id');
            // $table->unsignedBigInteger('t_bruto_id')->nullable();
            $table->foreignId('t_pot_id')->constrained('potongan')->onDelete('cascade');
            $table->integer('total_netto')->nullable();
            $table->timestamps(0);
            $table->date('tanggal_transfer')->nullable();
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');

            // $table->foreign('employee_id')->references('id')->on('employees');
            // $table->foreign('t_bruto_id')->references('id')->on('gaji_bruto');
            // $table->foreign('t_pot_id')->references('id')->on('potongan');
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
