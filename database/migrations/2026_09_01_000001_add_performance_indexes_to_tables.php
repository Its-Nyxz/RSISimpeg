<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add performance indexes.
     */
    public function up(): void
    {
        // 1. users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'status_karyawan') && Schema::hasColumn('users', 'unit_id')) {
                    $table->index(['status_karyawan', 'unit_id'], 'idx_users_status_unit');
                }
                if (Schema::hasColumn('users', 'status_karyawan')) {
                    $table->index('status_karyawan', 'idx_users_status_karyawan');
                }
                if (Schema::hasColumn('users', 'nip')) {
                    $table->index('nip', 'idx_users_nip');
                }
                if (Schema::hasColumn('users', 'name')) {
                    $table->index('name', 'idx_users_name');
                }
                if (Schema::hasColumn('users', 'fungsi_id')) {
                    $table->index('fungsi_id', 'idx_users_fungsi_id');
                }
                if (Schema::hasColumn('users', 'umum_id')) {
                    $table->index('umum_id', 'idx_users_umum_id');
                }
                if (Schema::hasColumn('users', 'gol_id')) {
                    $table->index('gol_id', 'idx_users_gol_id');
                }
            });
        }

        // 2. jadwal_absensis
        if (Schema::hasTable('jadwal_absensis')) {
            Schema::table('jadwal_absensis', function (Blueprint $table) {
                if (Schema::hasColumn('jadwal_absensis', 'user_id') && Schema::hasColumn('jadwal_absensis', 'tanggal_jadwal')) {
                    $table->index(['user_id', 'tanggal_jadwal'], 'idx_jadwal_user_tanggal');
                }
                if (Schema::hasColumn('jadwal_absensis', 'tanggal_jadwal')) {
                    $table->index('tanggal_jadwal', 'idx_jadwal_tanggal');
                }
                if (Schema::hasColumn('jadwal_absensis', 'shift_id')) {
                    $table->index('shift_id', 'idx_jadwal_shift_id');
                }
                if (Schema::hasColumn('jadwal_absensis', 'opsi_id')) {
                    $table->index('opsi_id', 'idx_jadwal_opsi_id');
                }
            });
        }

        // 3. absensi
        if (Schema::hasTable('absensi')) {
            Schema::table('absensi', function (Blueprint $table) {
                if (Schema::hasColumn('absensi', 'jadwal_id') && Schema::hasColumn('absensi', 'user_id')) {
                    $table->index(['jadwal_id', 'user_id'], 'idx_absensi_jadwal_user');
                }
                if (Schema::hasColumn('absensi', 'user_id') && Schema::hasColumn('absensi', 'created_at')) {
                    $table->index(['user_id', 'created_at'], 'idx_absensi_user_created_at');
                }
                if (Schema::hasColumn('absensi', 'user_id') && Schema::hasColumn('absensi', 'is_lembur')) {
                    $table->index(['user_id', 'is_lembur'], 'idx_absensi_user_is_lembur');
                }
                if (Schema::hasColumn('absensi', 'user_id') && Schema::hasColumn('absensi', 'late')) {
                    $table->index(['user_id', 'late'], 'idx_absensi_user_late');
                }
                if (Schema::hasColumn('absensi', 'user_id') && Schema::hasColumn('absensi', 'absent')) {
                    $table->index(['user_id', 'absent'], 'idx_absensi_user_absent');
                }
                if (Schema::hasColumn('absensi', 'status_absen_id')) {
                    $table->index('status_absen_id', 'idx_absensi_status_absen_id');
                }
            });
        }

        // 4. cuti_karyawans
        if (Schema::hasTable('cuti_karyawans')) {
            Schema::table('cuti_karyawans', function (Blueprint $table) {
                if (Schema::hasColumn('cuti_karyawans', 'user_id') && Schema::hasColumn('cuti_karyawans', 'status_cuti_id')) {
                    $table->index(['user_id', 'status_cuti_id'], 'idx_cuti_user_status');
                }
                if (Schema::hasColumn('cuti_karyawans', 'user_id') && Schema::hasColumn('cuti_karyawans', 'tanggal_mulai')) {
                    $table->index(['user_id', 'tanggal_mulai'], 'idx_cuti_user_tgl_mulai');
                }
                if (Schema::hasColumn('cuti_karyawans', 'status_cuti_id') && Schema::hasColumn('cuti_karyawans', 'tanggal_mulai')) {
                    $table->index(['status_cuti_id', 'tanggal_mulai'], 'idx_cuti_status_tgl_mulai');
                }
                if (Schema::hasColumn('cuti_karyawans', 'tanggal_mulai')) {
                    $table->index('tanggal_mulai', 'idx_cuti_tanggal_mulai');
                }
                if (Schema::hasColumn('cuti_karyawans', 'tanggal_selesai')) {
                    $table->index('tanggal_selesai', 'idx_cuti_tanggal_selesai');
                }
            });
        }

        // 5. izin_karyawans
        if (Schema::hasTable('izin_karyawans')) {
            Schema::table('izin_karyawans', function (Blueprint $table) {
                if (Schema::hasColumn('izin_karyawans', 'user_id') && Schema::hasColumn('izin_karyawans', 'status_izin_id') && Schema::hasColumn('izin_karyawans', 'tanggal_mulai')) {
                    $table->index(['user_id', 'status_izin_id', 'tanggal_mulai'], 'idx_izin_user_status_tgl_mulai');
                }
                if (Schema::hasColumn('izin_karyawans', 'status_izin_id') && Schema::hasColumn('izin_karyawans', 'tanggal_mulai')) {
                    $table->index(['status_izin_id', 'tanggal_mulai'], 'idx_izin_status_tgl_mulai');
                }
                if (Schema::hasColumn('izin_karyawans', 'tanggal_mulai')) {
                    $table->index('tanggal_mulai', 'idx_izin_tanggal_mulai');
                }
                if (Schema::hasColumn('izin_karyawans', 'tanggal_selesai')) {
                    $table->index('tanggal_selesai', 'idx_izin_tanggal_selesai');
                }
            });
        }

        // 6. tukar_jadwals
        if (Schema::hasTable('tukar_jadwals')) {
            Schema::table('tukar_jadwals', function (Blueprint $table) {
                if (Schema::hasColumn('tukar_jadwals', 'user_id') && Schema::hasColumn('tukar_jadwals', 'tanggal')) {
                    $table->index(['user_id', 'tanggal'], 'idx_tukar_jadwal_user_tanggal');
                }
                if (Schema::hasColumn('tukar_jadwals', 'is_approved') && Schema::hasColumn('tukar_jadwals', 'tanggal')) {
                    $table->index(['is_approved', 'tanggal'], 'idx_tukar_jadwal_approved_tgl');
                }
                if (Schema::hasColumn('tukar_jadwals', 'tanggal')) {
                    $table->index('tanggal', 'idx_tukar_jadwal_tanggal');
                }
            });
        }

        // 7. gaji_bruto
        if (Schema::hasTable('gaji_bruto')) {
            Schema::table('gaji_bruto', function (Blueprint $table) {
                if (Schema::hasColumn('gaji_bruto', 'user_id') && Schema::hasColumn('gaji_bruto', 'tahun_penggajian') && Schema::hasColumn('gaji_bruto', 'bulan_penggajian')) {
                    $table->index(['user_id', 'tahun_penggajian', 'bulan_penggajian'], 'idx_gaji_bruto_user_thn_bln');
                }
                if (Schema::hasColumn('gaji_bruto', 'tahun_penggajian') && Schema::hasColumn('gaji_bruto', 'bulan_penggajian')) {
                    $table->index(['tahun_penggajian', 'bulan_penggajian'], 'idx_gaji_bruto_thn_bln');
                }
            });
        }

        // 8. gaji_netto
        if (Schema::hasTable('gaji_netto')) {
            Schema::table('gaji_netto', function (Blueprint $table) {
                if (Schema::hasColumn('gaji_netto', 'bruto_id') && Schema::hasColumn('gaji_netto', 'status')) {
                    $table->index(['bruto_id', 'status'], 'idx_gaji_netto_bruto_status');
                }
                if (Schema::hasColumn('gaji_netto', 'status')) {
                    $table->index('status', 'idx_gaji_netto_status');
                }
                if (Schema::hasColumn('gaji_netto', 'tanggal_transfer')) {
                    $table->index('tanggal_transfer', 'idx_gaji_netto_tgl_transfer');
                }
            });
        }

        // 9. potongan
        if (Schema::hasTable('potongan')) {
            Schema::table('potongan', function (Blueprint $table) {
                if (Schema::hasColumn('potongan', 'bruto_id') && Schema::hasColumn('potongan', 'master_potongan_id')) {
                    $table->index(['bruto_id', 'master_potongan_id'], 'idx_potongan_bruto_master');
                }
                if (Schema::hasColumn('potongan', 'tahun_penggajian') && Schema::hasColumn('potongan', 'bulan_penggajian') && Schema::hasColumn('potongan', 'master_potongan_id')) {
                    $table->index(['tahun_penggajian', 'bulan_penggajian', 'master_potongan_id'], 'idx_potongan_thn_bln_master');
                }
            });
        }

        // 10. source_files
        if (Schema::hasTable('source_files')) {
            Schema::table('source_files', function (Blueprint $table) {
                if (Schema::hasColumn('source_files', 'user_id') && Schema::hasColumn('source_files', 'selesai')) {
                    $table->index(['user_id', 'selesai'], 'idx_source_files_user_selesai');
                }
                if (Schema::hasColumn('source_files', 'jenis_file_id') && Schema::hasColumn('source_files', 'selesai')) {
                    $table->index(['jenis_file_id', 'selesai'], 'idx_source_files_jenis_selesai');
                }
                if (Schema::hasColumn('source_files', 'selesai')) {
                    $table->index('selesai', 'idx_source_files_selesai');
                }
            });
        }

        // 11. sisa_cuti_tahunans
        if (Schema::hasTable('sisa_cuti_tahunans')) {
            Schema::table('sisa_cuti_tahunans', function (Blueprint $table) {
                if (Schema::hasColumn('sisa_cuti_tahunans', 'user_id') && Schema::hasColumn('sisa_cuti_tahunans', 'tahun')) {
                    $table->index(['user_id', 'tahun'], 'idx_sisa_cuti_user_tahun');
                }
            });
        }

        // 12. riwayat_approvals
        if (Schema::hasTable('riwayat_approvals')) {
            Schema::table('riwayat_approvals', function (Blueprint $table) {
                if (Schema::hasColumn('riwayat_approvals', 'cuti_id') && Schema::hasColumn('riwayat_approvals', 'approver_id')) {
                    $table->index(['cuti_id', 'approver_id'], 'idx_riwayat_appr_cuti_approver');
                }
                if (Schema::hasColumn('riwayat_approvals', 'approver_id') && Schema::hasColumn('riwayat_approvals', 'approve_at')) {
                    $table->index(['approver_id', 'approve_at'], 'idx_riwayat_appr_approver_at');
                }
                if (Schema::hasColumn('riwayat_approvals', 'status_approval')) {
                    $table->index('status_approval', 'idx_riwayat_appr_status');
                }
            });
        }

        // 13. riwayat_jabatans
        if (Schema::hasTable('riwayat_jabatans')) {
            Schema::table('riwayat_jabatans', function (Blueprint $table) {
                if (Schema::hasColumn('riwayat_jabatans', 'user_id') && Schema::hasColumn('riwayat_jabatans', 'tunjangan') && Schema::hasColumn('riwayat_jabatans', 'tanggal_selesai')) {
                    $table->index(['user_id', 'tunjangan', 'tanggal_selesai'], 'idx_riwayat_jab_user_tunj_selesai');
                }
                if (Schema::hasColumn('riwayat_jabatans', 'kategori_jabatan_id') && Schema::hasColumn('riwayat_jabatans', 'tanggal_selesai')) {
                    $table->index(['kategori_jabatan_id', 'tanggal_selesai'], 'idx_riwayat_jab_kat_selesai');
                }
                if (Schema::hasColumn('riwayat_jabatans', 'tanggal_mulai')) {
                    $table->index('tanggal_mulai', 'idx_riwayat_jab_tgl_mulai');
                }
            });
        }

        // 14. peringatan_karyawans
        if (Schema::hasTable('peringatan_karyawans')) {
            Schema::table('peringatan_karyawans', function (Blueprint $table) {
                if (Schema::hasColumn('peringatan_karyawans', 'user_id') && Schema::hasColumn('peringatan_karyawans', 'tanggal_sp')) {
                    $table->index(['user_id', 'tanggal_sp'], 'idx_sp_user_tanggal_sp');
                }
                if (Schema::hasColumn('peringatan_karyawans', 'tanggal_sp')) {
                    $table->index('tanggal_sp', 'idx_sp_tanggal_sp');
                }
            });
        }

        // 15. gapok_kontraks & penyesuaian
        if (Schema::hasTable('gapok_kontraks')) {
            Schema::table('gapok_kontraks', function (Blueprint $table) {
                if (Schema::hasColumn('gapok_kontraks', 'kategori_jabatan_id') && Schema::hasColumn('gapok_kontraks', 'min_masa_kerja') && Schema::hasColumn('gapok_kontraks', 'max_masa_kerja')) {
                    $table->index(['kategori_jabatan_id', 'min_masa_kerja', 'max_masa_kerja'], 'idx_gapok_kontrak_kat_masa');
                }
            });
        }
        if (Schema::hasTable('gapok_kontrak_penyesuaians')) {
            Schema::table('gapok_kontrak_penyesuaians', function (Blueprint $table) {
                if (Schema::hasColumn('gapok_kontrak_penyesuaians', 'gapok_kontrak_id') && Schema::hasColumn('gapok_kontrak_penyesuaians', 'tanggal_berlaku')) {
                    $table->index(['gapok_kontrak_id', 'tanggal_berlaku'], 'idx_gapok_penyesuaian_id_tgl');
                }
            });
        }

        // 16. t_gapok, t_penyesuaian, t_umum
        if (Schema::hasTable('t_gapok')) {
            Schema::table('t_gapok', function (Blueprint $table) {
                if (Schema::hasColumn('t_gapok', 'user_id') && Schema::hasColumn('t_gapok', 'status') && Schema::hasColumn('t_gapok', 'tanggal_kenaikan')) {
                    $table->index(['user_id', 'status', 'tanggal_kenaikan'], 'idx_t_gapok_user_status_tgl');
                }
                if (Schema::hasColumn('t_gapok', 'tanggal_kenaikan')) {
                    $table->index('tanggal_kenaikan', 'idx_t_gapok_tgl_kenaikan');
                }
            });
        }
        if (Schema::hasTable('t_penyesuaian')) {
            Schema::table('t_penyesuaian', function (Blueprint $table) {
                if (Schema::hasColumn('t_penyesuaian', 'user_id') && Schema::hasColumn('t_penyesuaian', 'tanggal_penyesuaian')) {
                    $table->index(['user_id', 'tanggal_penyesuaian'], 'idx_t_penyesuaian_user_tgl');
                }
            });
        }
        if (Schema::hasTable('t_umum')) {
            Schema::table('t_umum', function (Blueprint $table) {
                if (Schema::hasColumn('t_umum', 'user_id') && Schema::hasColumn('t_umum', 'umum_id')) {
                    $table->index(['user_id', 'umum_id'], 'idx_t_umum_user_umum');
                }
                if (Schema::hasColumn('t_umum', 'umum_id')) {
                    $table->index('umum_id', 'idx_t_umum_umum_id');
                }
            });
        }

        // 17. override_lokasis
        if (Schema::hasTable('override_lokasis')) {
            Schema::table('override_lokasis', function (Blueprint $table) {
                if (Schema::hasColumn('override_lokasis', 'user_id') && Schema::hasColumn('override_lokasis', 'jadwal_id')) {
                    $table->index(['user_id', 'jadwal_id'], 'idx_override_user_jadwal');
                }
                if (Schema::hasColumn('override_lokasis', 'user_id') && Schema::hasColumn('override_lokasis', 'created_at')) {
                    $table->index(['user_id', 'created_at'], 'idx_override_user_created');
                }
            });
        }

        // 18. notifications
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (Schema::hasColumn('notifications', 'notifiable_type') && Schema::hasColumn('notifications', 'notifiable_id') && Schema::hasColumn('notifications', 'read_at')) {
                    $table->index(['notifiable_type', 'notifiable_id', 'read_at'], 'idx_notifications_notifiable_read');
                }
            });
        }

        // 19. pjs
        if (Schema::hasTable('pjs')) {
            Schema::table('pjs', function (Blueprint $table) {
                if (Schema::hasColumn('pjs', 'assigned_at')) {
                    $table->index('assigned_at', 'idx_pjs_assigned_at');
                }
            });
        }

        // 20. holidays
        if (Schema::hasTable('holidays')) {
            Schema::table('holidays', function (Blueprint $table) {
                if (Schema::hasColumn('holidays', 'date')) {
                    $table->index('date', 'idx_holidays_date');
                }
            });
        }

        // 21. log
        if (Schema::hasTable('log')) {
            Schema::table('log', function (Blueprint $table) {
                if (Schema::hasColumn('log', 'table') && Schema::hasColumn('log', 'created_at')) {
                    $table->index(['table', 'created_at'], 'idx_log_table_created');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('idx_users_status_unit');
                $table->dropIndex('idx_users_status_karyawan');
                $table->dropIndex('idx_users_nip');
                $table->dropIndex('idx_users_name');
                $table->dropIndex('idx_users_fungsi_id');
                $table->dropIndex('idx_users_umum_id');
                $table->dropIndex('idx_users_gol_id');
            });
        }

        // 2. jadwal_absensis
        if (Schema::hasTable('jadwal_absensis')) {
            Schema::table('jadwal_absensis', function (Blueprint $table) {
                $table->dropIndex('idx_jadwal_user_tanggal');
                $table->dropIndex('idx_jadwal_tanggal');
                $table->dropIndex('idx_jadwal_shift_id');
                $table->dropIndex('idx_jadwal_opsi_id');
            });
        }

        // 3. absensi
        if (Schema::hasTable('absensi')) {
            Schema::table('absensi', function (Blueprint $table) {
                $table->dropIndex('idx_absensi_jadwal_user');
                $table->dropIndex('idx_absensi_user_created_at');
                $table->dropIndex('idx_absensi_user_is_lembur');
                $table->dropIndex('idx_absensi_user_late');
                $table->dropIndex('idx_absensi_user_absent');
                $table->dropIndex('idx_absensi_status_absen_id');
            });
        }

        // 4. cuti_karyawans
        if (Schema::hasTable('cuti_karyawans')) {
            Schema::table('cuti_karyawans', function (Blueprint $table) {
                $table->dropIndex('idx_cuti_user_status');
                $table->dropIndex('idx_cuti_user_tgl_mulai');
                $table->dropIndex('idx_cuti_status_tgl_mulai');
                $table->dropIndex('idx_cuti_tanggal_mulai');
                $table->dropIndex('idx_cuti_tanggal_selesai');
            });
        }

        // 5. izin_karyawans
        if (Schema::hasTable('izin_karyawans')) {
            Schema::table('izin_karyawans', function (Blueprint $table) {
                $table->dropIndex('idx_izin_user_status_tgl_mulai');
                $table->dropIndex('idx_izin_status_tgl_mulai');
                $table->dropIndex('idx_izin_tanggal_mulai');
                $table->dropIndex('idx_izin_tanggal_selesai');
            });
        }

        // 6. tukar_jadwals
        if (Schema::hasTable('tukar_jadwals')) {
            Schema::table('tukar_jadwals', function (Blueprint $table) {
                $table->dropIndex('idx_tukar_jadwal_user_tanggal');
                $table->dropIndex('idx_tukar_jadwal_approved_tgl');
                $table->dropIndex('idx_tukar_jadwal_tanggal');
            });
        }

        // 7. gaji_bruto
        if (Schema::hasTable('gaji_bruto')) {
            Schema::table('gaji_bruto', function (Blueprint $table) {
                $table->dropIndex('idx_gaji_bruto_user_thn_bln');
                $table->dropIndex('idx_gaji_bruto_thn_bln');
            });
        }

        // 8. gaji_netto
        if (Schema::hasTable('gaji_netto')) {
            Schema::table('gaji_netto', function (Blueprint $table) {
                $table->dropIndex('idx_gaji_netto_bruto_status');
                $table->dropIndex('idx_gaji_netto_status');
                $table->dropIndex('idx_gaji_netto_tgl_transfer');
            });
        }

        // 9. potongan
        if (Schema::hasTable('potongan')) {
            Schema::table('potongan', function (Blueprint $table) {
                $table->dropIndex('idx_potongan_bruto_master');
                $table->dropIndex('idx_potongan_thn_bln_master');
            });
        }

        // 10. source_files
        if (Schema::hasTable('source_files')) {
            Schema::table('source_files', function (Blueprint $table) {
                $table->dropIndex('idx_source_files_user_selesai');
                $table->dropIndex('idx_source_files_jenis_selesai');
                $table->dropIndex('idx_source_files_selesai');
            });
        }

        // 11. sisa_cuti_tahunans
        if (Schema::hasTable('sisa_cuti_tahunans')) {
            Schema::table('sisa_cuti_tahunans', function (Blueprint $table) {
                $table->dropIndex('idx_sisa_cuti_user_tahun');
            });
        }

        // 12. riwayat_approvals
        if (Schema::hasTable('riwayat_approvals')) {
            Schema::table('riwayat_approvals', function (Blueprint $table) {
                $table->dropIndex('idx_riwayat_appr_cuti_approver');
                $table->dropIndex('idx_riwayat_appr_approver_at');
                $table->dropIndex('idx_riwayat_appr_status');
            });
        }

        // 13. riwayat_jabatans
        if (Schema::hasTable('riwayat_jabatans')) {
            Schema::table('riwayat_jabatans', function (Blueprint $table) {
                $table->dropIndex('idx_riwayat_jab_user_tunj_selesai');
                $table->dropIndex('idx_riwayat_jab_kat_selesai');
                $table->dropIndex('idx_riwayat_jab_tgl_mulai');
            });
        }

        // 14. peringatan_karyawans
        if (Schema::hasTable('peringatan_karyawans')) {
            Schema::table('peringatan_karyawans', function (Blueprint $table) {
                $table->dropIndex('idx_sp_user_tanggal_sp');
                $table->dropIndex('idx_sp_tanggal_sp');
            });
        }

        // 15. gapok_kontraks & penyesuaian
        if (Schema::hasTable('gapok_kontraks')) {
            Schema::table('gapok_kontraks', function (Blueprint $table) {
                $table->dropIndex('idx_gapok_kontrak_kat_masa');
            });
        }
        if (Schema::hasTable('gapok_kontrak_penyesuaians')) {
            Schema::table('gapok_kontrak_penyesuaians', function (Blueprint $table) {
                $table->dropIndex('idx_gapok_penyesuaian_id_tgl');
            });
        }

        // 16. t_gapok, t_penyesuaian, t_umum
        if (Schema::hasTable('t_gapok')) {
            Schema::table('t_gapok', function (Blueprint $table) {
                $table->dropIndex('idx_t_gapok_user_status_tgl');
                $table->dropIndex('idx_t_gapok_tgl_kenaikan');
            });
        }
        if (Schema::hasTable('t_penyesuaian')) {
            Schema::table('t_penyesuaian', function (Blueprint $table) {
                $table->dropIndex('idx_t_penyesuaian_user_tgl');
            });
        }
        if (Schema::hasTable('t_umum')) {
            Schema::table('t_umum', function (Blueprint $table) {
                $table->dropIndex('idx_t_umum_user_umum');
                $table->dropIndex('idx_t_umum_umum_id');
            });
        }

        // 17. override_lokasis
        if (Schema::hasTable('override_lokasis')) {
            Schema::table('override_lokasis', function (Blueprint $table) {
                $table->dropIndex('idx_override_user_jadwal');
                $table->dropIndex('idx_override_user_created');
            });
        }

        // 18. notifications
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndex('idx_notifications_notifiable_read');
            });
        }

        // 19. pjs
        if (Schema::hasTable('pjs')) {
            Schema::table('pjs', function (Blueprint $table) {
                $table->dropIndex('idx_pjs_assigned_at');
            });
        }

        // 20. holidays
        if (Schema::hasTable('holidays')) {
            Schema::table('holidays', function (Blueprint $table) {
                $table->dropIndex('idx_holidays_date');
            });
        }

        // 21. log
        if (Schema::hasTable('log')) {
            Schema::table('log', function (Blueprint $table) {
                $table->dropIndex('idx_log_table_created');
            });
        }
    }
};
