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
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique(); // tambahkan slug yang unik dan nullable
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerjas')->onDelete('cascade');
            $table->foreignId('jabatan_id')->nullable()->constrained('kategori_jabatans')->onDelete('cascade');
            $table->foreignId('fungsi_id')->nullable();
            $table->foreignId('umum_id')->nullable();
            $table->foreignId('trans_id')->nullable()->constrained('master_trans')->onDelete('cascade');
            $table->foreignId('khusus_id')->nullable()->constrained('master_khusus')->onDelete('cascade');
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_karyawans')->onDelete('cascade');
            // $table->foreignId('gol_id')->nullable()->constrained('master_umum')->onDelete('cascade');
            $table->unsignedBigInteger('gol_id')->nullable();
            $table->string('nip')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('tmt')->nullable();
            $table->string('jk')->nullable();
            $table->dateTime('pensiun')->nullable();
            $table->string('tempat')->nullable();
            $table->dateTime('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->integer('no_rek')->nullable();
            // $table->integer('pend_awal')->nullable();
            $table->dateTime('tanggal_tetap')->nullable();
            $table->foreignId('pendidikan')->nullable()->constrained('master_pendidikan');
            $table->string('pendidikan_penyesuaian')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategoripphs')->onDelete('cascade');
            // $table->integer('pend_penyesuaian')->nullable();
            // $table->string('pendidikan')->nullable();
            $table->dateTime('tgl_penyesuaian')->nullable();
            $table->integer('masa_kerja')->default(value: 0)->nullable();
            $table->integer('status')->default(value: 0)->nullable();
            $table->integer('jatah_cuti_tahunan')->default(12); // Jatah cuti default
            $table->integer('sisa_cuti_tahunan')->default(12); // Sisa cuti tahunan
            $table->timestamps();

            // $table->foreign('jabatan_id')->references('id')->on('master_jabatan');
            // $table->foreign('fungsi_id')->references('id')->on('master_fungsi');
            // $table->foreign('trans_id')->references('id')->on('master_trans');
            // $table->foreign('khusus_id')->references('id')->on('master_khusus');
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
