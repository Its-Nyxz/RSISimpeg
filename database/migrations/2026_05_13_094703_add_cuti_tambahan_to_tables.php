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
        Schema::table('cuti_karyawans', function (Blueprint $table) {
            $table->string('kategori_cuti')->default('tahunan')->after('jenis_cuti_id');
        });

        Schema::table('sisa_cuti_tahunans', function (Blueprint $table) {
            $table->integer('cuti_tambahan')->default(0)->after('sisa_cuti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_karyawans', function (Blueprint $table) {
            $table->dropColumn('kategori_cuti');
        });

        Schema::table('sisa_cuti_tahunans', function (Blueprint $table) {
            $table->dropColumn('cuti_tambahan');
        });
    }
};
