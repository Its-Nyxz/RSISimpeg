# Panduan Penggunaan & Pengaturan Fitur Sinkronisasi Cuti ke Jadwal

Dokumen ini menjelaskan langkah-langkah pengaturan permission dan penggunaan fitur **Sinkronisasi Cuti ke Jadwal (Shift C)** pada aplikasi SIMPEG RSI.

---

## 1. Persiapan Awal di Server (Menjalankan Seeder)

Jalankan perintah seeder berikut di terminal server untuk menambahkan permission baru `sinkron-cuti` ke dalam sistem:

```bash
php artisan db:seed --class=SinkronCutiPermissionSeeder
```

> **Catatan:**
> - Seeder ini aman dijalankan karena menggunakan `firstOrCreate` (tidak akan menduplikasi permission yang sudah ada).
> - Secara default, permission `sinkron-cuti` otomatis diberikan kepada role **Super Admin** dan **Administrator** agar menu/tombol tidak terkunci.

---

## 2. Pengaturan Hak Akses (Permission Setting)

Pihak Administrator dapat mengatur siapa saja yang berhak melihat dan menekan tombol **Sinkron Cuti**:

1. Masuk ke menu **Hak Akses / Role & Permission**.
2. Pilih role yang ingin diberikan wewenang (misalnya: *Kepala Unit*, *Kepala Seksi Kepegawaian*, dll).
3. Centang permission **`sinkron-cuti`**.
4. Simpan perubahan.

> **Catatan:** User yang tidak memiliki permission `sinkron-cuti` tidak akan melihat tombol **Sinkron Cuti** di halaman Data Jadwal. Jika diakses paksa via sistem, request akan otomatis ditolak dengan pesan peringatan.

---

## 3. Cara Penggunaan Fitur

Tersedia 2 cara untuk melakukan sinkronisasi:

### Cara 1: Melalui Web (Halaman Data Jadwal)
1. Buka menu **Data Jadwal**.
2. Pastikan akun memiliki permission `sinkron-cuti`.
3. Klik tombol biru **Sinkron Cuti** (di samping tombol Import Excel).
4. Tunggu beberapa detik hingga proses selesai dan muncul notifikasi sukses.

### Cara 2: Melalui Terminal Server (Artisan Command)
Digunakan jika administrator ingin menjalankan sinkronisasi langsung di background server:

- **Sinkronisasi semua unit dan semua status cuti (Disetujui):**
  ```bash
  php artisan cuti:sync-jadwal --all
  ```

- **Sinkronisasi khusus unit tertentu (contoh: Unit IRJ):**
  ```bash
  php artisan cuti:sync-jadwal --unit=IRJ
  ```
  *Atau menggunakan ID unit kerja:*
  ```bash
  php artisan cuti:sync-jadwal --unit=18
  ```

---

## 4. Perilaku Otomatis Sistem (Otomasi Berjalan)
Selain tombol sinkron manual di atas, sistem sudah dilengkapi otomasi:
1. **Approval Cuti Real-time:** Setiap pengajuan cuti yang disetujui (baik approval bertingkat maupun final), jadwal karyawan pada rentang tanggal cuti akan otomatis berubah menjadi shift **C** dan tercatat absensinya.
2. **Proteksi Import Jadwal Excel:** Saat admin unit mengimpor file jadwal baru, shift cuti yang sudah disetujui tidak akan tertimpa oleh shift kerja reguler (`P`, `S`, `M`, dll).
