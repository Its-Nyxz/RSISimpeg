<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_karyawans', function (Blueprint $table) {
            $table->string('bukti_cuti')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cuti_karyawans', function (Blueprint $table) {
            $table->dropColumn('bukti_cuti');
        });
    }
};