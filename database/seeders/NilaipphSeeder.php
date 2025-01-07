<?php

namespace Database\Seeders;

use App\Models\Nilaipph;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaipphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            // Data Kategori A (kategori_id = 1)
            ['kategori_id' => 1, 'upper_limit' => 0.00, 'tax_rate' => 0.0000],
            ['kategori_id' => 1, 'upper_limit' => 5400000.00, 'tax_rate' => 0.0025],
            ['kategori_id' => 1, 'upper_limit' => 5650000.00, 'tax_rate' => 0.0050],
            ['kategori_id' => 1, 'upper_limit' => 5950000.00, 'tax_rate' => 0.0075],
            ['kategori_id' => 1, 'upper_limit' => 6300000.00, 'tax_rate' => 0.0100],
            ['kategori_id' => 1, 'upper_limit' => 6750000.00, 'tax_rate' => 0.0125],
            // Tambahkan data lainnya untuk kategori A...

            // Data Kategori B (kategori_id = 5)
            ['kategori_id' => 5, 'upper_limit' => 0.00, 'tax_rate' => 0.0000],
            ['kategori_id' => 5, 'upper_limit' => 6200000.00, 'tax_rate' => 0.0025],
            ['kategori_id' => 5, 'upper_limit' => 6500000.00, 'tax_rate' => 0.0050],
            ['kategori_id' => 5, 'upper_limit' => 6850000.00, 'tax_rate' => 0.0075],
            ['kategori_id' => 5, 'upper_limit' => 7300000.00, 'tax_rate' => 0.0100],
            ['kategori_id' => 5, 'upper_limit' => 9200000.00, 'tax_rate' => 0.0150],
            // Tambahkan data lainnya untuk kategori B...

            // Data Kategori C (kategori_id = 10)
            ['kategori_id' => 10, 'upper_limit' => 0.00, 'tax_rate' => 0.0000],
            ['kategori_id' => 10, 'upper_limit' => 6600000.00, 'tax_rate' => 0.0025],
            ['kategori_id' => 10, 'upper_limit' => 6950000.00, 'tax_rate' => 0.0050],
            ['kategori_id' => 10, 'upper_limit' => 7350000.00, 'tax_rate' => 0.0075],
            ['kategori_id' => 10, 'upper_limit' => 7800000.00, 'tax_rate' => 0.0100],
            ['kategori_id' => 10, 'upper_limit' => 8850000.00, 'tax_rate' => 0.0125],
            // Tambahkan data lainnya untuk kategori C...
        ];

        foreach ($datas as $data) {
            Nilaipph::create($data);
        }
    }
}
