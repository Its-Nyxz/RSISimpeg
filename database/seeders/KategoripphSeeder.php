<?php

namespace Database\Seeders;

use App\Models\Kategoripph;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoripphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Root Kategori A
        $kategoriA = Kategoripph::create([
            'nama' => 'Kategori A',
            'keterangan' => 'Tarif Efektif Bulanan Kategori A untuk wajib pajak tidak kena pajak dengan status tertentu',
            'parent_id' => null,
        ]);

        // Subkategori A
        Kategoripph::create(['nama' => 'TK0', 'keterangan' => 'Tidak kawin tanpa tanggungan', 'parent_id' => $kategoriA->id]);
        Kategoripph::create(['nama' => 'TK1', 'keterangan' => 'Tidak kawin dengan tanggungan 1 orang', 'parent_id' => $kategoriA->id]);
        Kategoripph::create(['nama' => 'K0', 'keterangan' => 'Kawin tanpa tanggungan', 'parent_id' => $kategoriA->id]);

        // Root Kategori B
        $kategoriB = Kategoripph::create([
            'nama' => 'Kategori B',
            'keterangan' => 'Tarif Efektif Bulanan Kategori B untuk wajib pajak tidak kena pajak dengan status tertentu',
            'parent_id' => null,
        ]);

        // Subkategori B
        Kategoripph::create(['nama' => 'TK2', 'keterangan' => 'Tidak kawin dengan tanggungan 2 orang', 'parent_id' => $kategoriB->id]);
        Kategoripph::create(['nama' => 'TK3', 'keterangan' => 'Tidak kawin dengan tanggungan 3 orang', 'parent_id' => $kategoriB->id]);
        Kategoripph::create(['nama' => 'K1', 'keterangan' => 'Kawin dengan tanggungan 1 orang', 'parent_id' => $kategoriB->id]);
        Kategoripph::create(['nama' => 'K2', 'keterangan' => 'Kawin dengan tanggungan 2 orang', 'parent_id' => $kategoriB->id]);

        // Root Kategori C
        $kategoriC = Kategoripph::create([
            'nama' => 'Kategori C',
            'keterangan' => 'Tarif Efektif Bulanan Kategori C untuk wajib pajak tidak kena pajak dengan status tertentu',
            'parent_id' => null,
        ]);

        // Subkategori C
        Kategoripph::create(['nama' => 'K3', 'keterangan' => 'Kawin dengan tanggungan 3 orang', 'parent_id' => $kategoriC->id]);
    }
}
