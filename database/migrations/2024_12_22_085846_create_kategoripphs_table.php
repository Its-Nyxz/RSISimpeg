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
        Schema::create('kategoripphs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('kategoripphs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoripphs');
    }
};
