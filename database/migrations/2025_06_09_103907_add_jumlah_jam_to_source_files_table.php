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
        Schema::table('source_files', function (Blueprint $table) {
            $table->integer('jumlah_jam')->nullable()->after('selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('source_files', function (Blueprint $table) {
            $table->dropColumn('jumlah_jam');
        });
    }
};
