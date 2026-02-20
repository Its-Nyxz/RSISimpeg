<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrutanKeuangan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('urutan_keuangan_user')->truncate();

        $daftarStatus = [1, 2, 3, 4];

        foreach ($daftarStatus as $status) {
            // Ambil user berdasarkan status dan urutkan nama
            $users = User::where('jenis_id', $status)
                ->orderBy('name', 'asc')
                ->get();

            // Simpan ke database dengan urutan yang dimulai dari 1 untuk setiap status
            foreach ($users as $index => $user) {
                DB::table('urutan_keuangan_user')->insert([
                    'user_id'    => $user->id,
                    'urutan'     => $index + 1, // Reset ke 1 setiap ganti status
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
