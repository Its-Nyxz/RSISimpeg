<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_cutis', function (Blueprint $table) {
            $table->boolean('aktif')->default(true);
        });

        Schema::table('jenis_izins', function (Blueprint $table) {
            $table->boolean('aktif')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('jenis_cutis', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });

        Schema::table('jenis_izins', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
    }
};
