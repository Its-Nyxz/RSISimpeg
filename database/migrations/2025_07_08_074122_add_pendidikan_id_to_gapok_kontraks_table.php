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
        Schema::table('gapok_kontraks', function (Blueprint $table) {
            $table->foreignId('pendidikan_id')
                ->nullable()
                ->after('kategori_jabatan_id')
                ->constrained('master_pendidikan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gapok_kontraks', function (Blueprint $table) {
            $table->dropForeign(['pendidikan_id']);
            $table->dropColumn('pendidikan_id');
        });
    }
};
