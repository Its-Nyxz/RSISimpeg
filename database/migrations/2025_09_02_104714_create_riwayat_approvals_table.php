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
        Schema::create('riwayat_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'cuti_id');
            $table->unsignedBigInteger(column: 'approver_id');
            $table->string('status_approval');
            $table->text('catatan')->nullable();
            $table->timestamp('approve_at');
            $table->timestamps();

            $table->foreign('cuti_id')->references('id')->on('cuti_karyawans')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_approvals');
    }
};
