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
        Schema::table('absensi', function (Blueprint $table) {
            // ubah kolom ke BIGINT, nullable biar bisa kosong
            $table->bigInteger('time_in')->unsigned()->nullable()->change();
            $table->bigInteger('time_out')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            // rollback jadi varchar(255)
            $table->string('time_in', 255)->nullable()->change();
            $table->string('time_out', 255)->nullable()->change();
        });
    }
};
