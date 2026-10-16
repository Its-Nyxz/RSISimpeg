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
        Schema::create('kpi_penilaians', function (Blueprint $table) {
            $table->id();

            // Yang Dinilai (Karyawan)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('posisi')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->decimal('gaji', 15, 2)->nullable();
            $table->decimal('bonus', 15, 2)->nullable();

            // Pejabat Penilai
            $table->foreignId('penilai_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('penilai_nama')->nullable();
            $table->string('penilai_nik')->nullable();
            $table->string('penilai_jabatan')->nullable();
            $table->string('penilai_unit_kerja')->nullable();

            // Atasan Pejabat Penilai
            $table->foreignId('atasan_penilai_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('atasan_penilai_nama')->nullable();
            $table->string('atasan_penilai_nik')->nullable();
            $table->string('atasan_penilai_jabatan')->nullable();
            $table->string('atasan_penilai_unit_kerja')->nullable();

            // Periode & Tanggal
            $table->integer('periode_bulan')->nullable();
            $table->integer('periode_tahun')->nullable();
            $table->date('tanggal_penilaian')->nullable();

            // Total & Kalkulasi Skor
            $table->decimal('total_bobot', 8, 2)->default(0);
            $table->decimal('total_skor', 8, 2)->default(0);

            // Kesimpulan Penerimaan
            $table->decimal('anggaran_tunjangan', 15, 2)->nullable()->default(0);
            $table->decimal('persentase_tunjangan', 8, 2)->nullable()->default(0);
            $table->decimal('tunjangan_diterima', 15, 2)->nullable()->default(0);

            $table->string('status')->default('draft');
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_penilaians');
    }
};
