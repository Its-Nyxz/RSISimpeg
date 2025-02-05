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
        Schema::create('proposionalitas_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerjas')->onDelete('cascade');
            $table->morphs('proposable'); // polymorphic relationship
            $table->decimal('min_limit')->nullable(); // Minimal batas poin
            $table->decimal('point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposionalitas_points');
    }
};
