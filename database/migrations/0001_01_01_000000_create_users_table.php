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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->unsignedBigInteger('fungsi_id')->nullable();
            $table->unsignedBigInteger('trans_id')->nullable();
            $table->unsignedBigInteger('khusus_id')->nullable();
            $table->unsignedBigInteger('gol_id')->nullable();
            $table->string('nip')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('tmt')->nullable();
            $table->string('jk')->nullable();
            $table->timestamp('pensiun')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            // $table->integer('pend_awal')->nullable();
            $table->foreignId('pend_awal')->nullable()->constrained('master_pendidikan');
            $table->foreignId('pend_penyesuaian')->nullable()->constrained('master_pendidikan');
            $table->foreignId('pendidikan')->nullable()->constrained('master_pendidikan');
            // $table->integer('pend_penyesuaian')->nullable();
            // $table->string('pendidikan')->nullable();
            $table->dateTime('tgl_penyesuaian')->nullable();
            $table->integer('masa_kerja')->default(value: 0)->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();

            $table->foreign('jabatan_id')->references('id')->on('master_jabatan');
            $table->foreign('fungsi_id')->references('id')->on('master_fungsi');
            $table->foreign('trans_id')->references('id')->on('master_trans');
            $table->foreign('khusus_id')->references('id')->on('master_khusus');
            // $table->foreign('gol_id')->references('id')->on('master_golongan');
            // $table->foreign('pend_awal')->references('id')->on('master_pendidikan');
            // $table->foreign('pend_penyesuaian')->references('id')->on('master_penyesuaian');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
