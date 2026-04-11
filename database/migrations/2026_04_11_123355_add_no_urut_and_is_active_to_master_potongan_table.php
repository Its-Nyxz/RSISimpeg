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
        Schema::table('master_potongan', function (Blueprint $table) {
            $table->integer('no_urut')->nullable()->after('nominal');
            $table->boolean('is_active')->nullable()->after('no_urut');
        });
    }

    public function down(): void
    {
        Schema::table('master_potongan', function (Blueprint $table) {
            $table->dropColumn(['no_urut', 'is_active']);
        });
    }
};
