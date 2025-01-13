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
            ['kategori_id' => 1, 'upper_limit' => 7500000.00, 'tax_rate' => 0.0150],
            ['kategori_id' => 1, 'upper_limit' => 8550000.00, 'tax_rate' => 0.0175],
            ['kategori_id' => 1, 'upper_limit' => 9650000.00, 'tax_rate' => 0.0200],
            ['kategori_id' => 1, 'upper_limit' => 10050000.00, 'tax_rate' => 0.0225],
            ['kategori_id' => 1, 'upper_limit' => 10350000.00, 'tax_rate' => 0.0250],
            ['kategori_id' => 1, 'upper_limit' => 10700000.00, 'tax_rate' => 0.0300],
            ['kategori_id' => 1, 'upper_limit' => 11050000.00, 'tax_rate' => 0.0350],
            ['kategori_id' => 1, 'upper_limit' => 11600000.00, 'tax_rate' => 0.0400],
            ['kategori_id' => 1, 'upper_limit' => 12500000.00, 'tax_rate' => 0.0500],
            ['kategori_id' => 1, 'upper_limit' => 13750000.00, 'tax_rate' => 0.0600],
            ['kategori_id' => 1, 'upper_limit' => 15100000.00, 'tax_rate' => 0.0700],
            ['kategori_id' => 1, 'upper_limit' => 16950000.00, 'tax_rate' => 0.0800],
            ['kategori_id' => 1, 'upper_limit' => 19750000.00, 'tax_rate' => 0.0900],
            ['kategori_id' => 1, 'upper_limit' => 24150000.00, 'tax_rate' => 0.1000],
            ['kategori_id' => 1, 'upper_limit' => 26450000.00, 'tax_rate' => 0.1100],
            ['kategori_id' => 1, 'upper_limit' => 28000000.00, 'tax_rate' => 0.1200],
            ['kategori_id' => 1, 'upper_limit' => 30050000.00, 'tax_rate' => 0.1300],
            ['kategori_id' => 1, 'upper_limit' => 32400000.00, 'tax_rate' => 0.1400],
            ['kategori_id' => 1, 'upper_limit' => 35400000.00, 'tax_rate' => 0.1500],
            ['kategori_id' => 1, 'upper_limit' => 39100000.00, 'tax_rate' => 0.1600],
            ['kategori_id' => 1, 'upper_limit' => 43850000.00, 'tax_rate' => 0.1700],
            ['kategori_id' => 1, 'upper_limit' => 47800000.00, 'tax_rate' => 0.1800],
            ['kategori_id' => 1, 'upper_limit' => 51400000.00, 'tax_rate' => 0.1900],
            ['kategori_id' => 1, 'upper_limit' => 56300000.00, 'tax_rate' => 0.2000],
            ['kategori_id' => 1, 'upper_limit' => 62200000.00, 'tax_rate' => 0.2100],
            ['kategori_id' => 1, 'upper_limit' => 68600000.00, 'tax_rate' => 0.2200],
            ['kategori_id' => 1, 'upper_limit' => 77500000.00, 'tax_rate' => 0.2300],
            ['kategori_id' => 1, 'upper_limit' => 89000000.00, 'tax_rate' => 0.2400],
            ['kategori_id' => 1, 'upper_limit' => 103000000.00, 'tax_rate' => 0.2500],
            ['kategori_id' => 1, 'upper_limit' => 125000000.00, 'tax_rate' => 0.2600],
            ['kategori_id' => 1, 'upper_limit' => 157000000.00, 'tax_rate' => 0.2700],
            ['kategori_id' => 1, 'upper_limit' => 206000000.00, 'tax_rate' => 0.2800],
            ['kategori_id' => 1, 'upper_limit' => 337000000.00, 'tax_rate' => 0.2900],
            ['kategori_id' => 1, 'upper_limit' => 454000000.00, 'tax_rate' => 0.3000],
            ['kategori_id' => 1, 'upper_limit' => 550000000.00, 'tax_rate' => 0.3100],
            ['kategori_id' => 1, 'upper_limit' => 695000000.00, 'tax_rate' => 0.3200],
            ['kategori_id' => 1, 'upper_limit' => 910000000.00, 'tax_rate' => 0.3300],
            ['kategori_id' => 1, 'upper_limit' => 1400000000.00, 'tax_rate' => 0.3400],
            // Tambahkan data lainnya untuk kategori A...

            // Data Kategori B (kategori_id = 5)
            ['kategori_id' => 5, 'upper_limit' => 0.00, 'tax_rate' => 0.0000],
            ['kategori_id' => 5, 'upper_limit' => 6200000.00, 'tax_rate' => 0.0025],
            ['kategori_id' => 5, 'upper_limit' => 6500000.00, 'tax_rate' => 0.0050],
            ['kategori_id' => 5, 'upper_limit' => 6850000.00, 'tax_rate' => 0.0075],
            ['kategori_id' => 5, 'upper_limit' => 7300000.00, 'tax_rate' => 0.0100],
            ['kategori_id' => 5, 'upper_limit' => 9200000.00, 'tax_rate' => 0.0150],
            ['kategori_id' => 5, 'upper_limit' => 10750000.00, 'tax_rate' => 0.0200],
            ['kategori_id' => 5, 'upper_limit' => 10750000.00, 'tax_rate' => 0.0200],
            ['kategori_id' => 5, 'upper_limit' => 11250000.00, 'tax_rate' => 0.0250],
            ['kategori_id' => 5, 'upper_limit' => 11600000.00, 'tax_rate' => 0.0300],
            ['kategori_id' => 5, 'upper_limit' => 12600000.00, 'tax_rate' => 0.0400],
            ['kategori_id' => 5, 'upper_limit' => 13600000.00, 'tax_rate' => 0.0500],
            ['kategori_id' => 5, 'upper_limit' => 14950000.00, 'tax_rate' => 0.0600],
            ['kategori_id' => 5, 'upper_limit' => 16400000.00, 'tax_rate' => 0.0700],
            ['kategori_id' => 5, 'upper_limit' => 18450000.00, 'tax_rate' => 0.0800],
            ['kategori_id' => 5, 'upper_limit' => 21850000.00, 'tax_rate' => 0.0900],
            ['kategori_id' => 5, 'upper_limit' => 26000000.00, 'tax_rate' => 0.1000],
            ['kategori_id' => 5, 'upper_limit' => 27700000.00, 'tax_rate' => 0.1100],
            ['kategori_id' => 5, 'upper_limit' => 29350000.00, 'tax_rate' => 0.1200],
            ['kategori_id' => 5, 'upper_limit' => 31450000.00, 'tax_rate' => 0.1300],
            ['kategori_id' => 5, 'upper_limit' => 33950000.00, 'tax_rate' => 0.1400],
            ['kategori_id' => 5, 'upper_limit' => 37100000.00, 'tax_rate' => 0.1500],
            ['kategori_id' => 5, 'upper_limit' => 41100000.00, 'tax_rate' => 0.1600],
            ['kategori_id' => 5, 'upper_limit' => 45800000.00, 'tax_rate' => 0.1700],
            ['kategori_id' => 5, 'upper_limit' => 49500000.00, 'tax_rate' => 0.1800],
            ['kategori_id' => 5, 'upper_limit' => 53800000.00, 'tax_rate' => 0.1900],
            ['kategori_id' => 5, 'upper_limit' => 58500000.00, 'tax_rate' => 0.2000],
            ['kategori_id' => 5, 'upper_limit' => 64000000.00, 'tax_rate' => 0.2100],
            ['kategori_id' => 5, 'upper_limit' => 64000000.00, 'tax_rate' => 0.2200],
            ['kategori_id' => 5, 'upper_limit' => 80000000.00, 'tax_rate' => 0.2300],
            ['kategori_id' => 5, 'upper_limit' => 93000000.00, 'tax_rate' => 0.2400],
            ['kategori_id' => 5, 'upper_limit' => 109000000.00, 'tax_rate' => 0.2500],
            ['kategori_id' => 5, 'upper_limit' => 129000000.00, 'tax_rate' => 0.2600],
            ['kategori_id' => 5, 'upper_limit' => 163000000.00, 'tax_rate' => 0.2700],
            ['kategori_id' => 5, 'upper_limit' => 211000000.00, 'tax_rate' => 0.2800],
            ['kategori_id' => 5, 'upper_limit' => 374000000.00, 'tax_rate' => 0.2900],
            ['kategori_id' => 5, 'upper_limit' => 459000000.00, 'tax_rate' => 0.3000],
            ['kategori_id' => 5, 'upper_limit' => 555000000.00, 'tax_rate' => 0.3100],
            ['kategori_id' => 5, 'upper_limit' => 704000000.00, 'tax_rate' => 0.3200],
            ['kategori_id' => 5, 'upper_limit' => 957000000.00, 'tax_rate' => 0.3300],
            ['kategori_id' => 5, 'upper_limit' => 1405000000.00, 'tax_rate' => 0.3400],
            // Tambahkan data lainnya untuk kategori B...

            // Data Kategori C (kategori_id = 10)
            ['kategori_id' => 10, 'upper_limit' => 0.00, 'tax_rate' => 0.0000],
            ['kategori_id' => 10, 'upper_limit' => 6600000.00, 'tax_rate' => 0.0025],
            ['kategori_id' => 10, 'upper_limit' => 6950000.00, 'tax_rate' => 0.0050],
            ['kategori_id' => 10, 'upper_limit' => 7350000.00, 'tax_rate' => 0.0075],
            ['kategori_id' => 10, 'upper_limit' => 7800000.00, 'tax_rate' => 0.0100],
            ['kategori_id' => 10, 'upper_limit' => 8850000.00, 'tax_rate' => 0.0125],
            ['kategori_id' => 10, 'upper_limit' => 9800000.00, 'tax_rate' => 0.0150],
            ['kategori_id' => 10, 'upper_limit' => 10950000.00, 'tax_rate' => 0.0175],
            ['kategori_id' => 10, 'upper_limit' => 11200000.00, 'tax_rate' => 0.0200],
            ['kategori_id' => 10, 'upper_limit' => 12050000.00, 'tax_rate' => 0.0300],
            ['kategori_id' => 10, 'upper_limit' => 12950000.00, 'tax_rate' => 0.0400],
            ['kategori_id' => 10, 'upper_limit' => 14150000.00, 'tax_rate' => 0.0500],
            ['kategori_id' => 10, 'upper_limit' => 15500000.00, 'tax_rate' => 0.0600],
            ['kategori_id' => 10, 'upper_limit' => 17050000.00, 'tax_rate' => 0.0700],
            ['kategori_id' => 10, 'upper_limit' => 19500000.00, 'tax_rate' => 0.0800],
            ['kategori_id' => 10, 'upper_limit' => 22700000.00, 'tax_rate' => 0.0900],
            ['kategori_id' => 10, 'upper_limit' => 26600000.00, 'tax_rate' => 0.1000],
            ['kategori_id' => 10, 'upper_limit' => 28100000.00, 'tax_rate' => 0.1100],
            ['kategori_id' => 10, 'upper_limit' => 30100000.00, 'tax_rate' => 0.1200],
            ['kategori_id' => 10, 'upper_limit' => 32600000.00, 'tax_rate' => 0.1300],
            ['kategori_id' => 10, 'upper_limit' => 35400000.00, 'tax_rate' => 0.1400],
            ['kategori_id' => 10, 'upper_limit' => 38900000.00, 'tax_rate' => 0.1500],
            ['kategori_id' => 10, 'upper_limit' => 43000000.00, 'tax_rate' => 0.1600],
            ['kategori_id' => 10, 'upper_limit' => 47400000.00, 'tax_rate' => 0.1700],
            ['kategori_id' => 10, 'upper_limit' => 51200000.00, 'tax_rate' => 0.1800],
            ['kategori_id' => 10, 'upper_limit' => 55800000.00, 'tax_rate' => 0.1900],
            ['kategori_id' => 10, 'upper_limit' => 60400000.00, 'tax_rate' => 0.2000],
            ['kategori_id' => 10, 'upper_limit' => 66700000.00, 'tax_rate' => 0.2100],
            ['kategori_id' => 10, 'upper_limit' => 74500000.00, 'tax_rate' => 0.2200],
            ['kategori_id' => 10, 'upper_limit' => 83200000.00, 'tax_rate' => 0.2300],
            ['kategori_id' => 10, 'upper_limit' => 95600000.00, 'tax_rate' => 0.2400],
            ['kategori_id' => 10, 'upper_limit' => 110000000.00, 'tax_rate' => 0.2500],
            ['kategori_id' => 10, 'upper_limit' => 134000000.00, 'tax_rate' => 0.2600],
            ['kategori_id' => 10, 'upper_limit' => 169000000.00, 'tax_rate' => 0.2700],
            ['kategori_id' => 10, 'upper_limit' => 221000000.00, 'tax_rate' => 0.2800],
            ['kategori_id' => 10, 'upper_limit' => 390000000.00, 'tax_rate' => 0.2900],
            ['kategori_id' => 10, 'upper_limit' => 463000000.00, 'tax_rate' => 0.3000],
            ['kategori_id' => 10, 'upper_limit' => 561000000.00, 'tax_rate' => 0.3100],
            ['kategori_id' => 10, 'upper_limit' => 709000000.00, 'tax_rate' => 0.3200],
            ['kategori_id' => 10, 'upper_limit' => 965000000.00, 'tax_rate' => 0.3300],
            ['kategori_id' => 10, 'upper_limit' => 1419000000.00, 'tax_rate' => 0.3400],
            // Tambahkan data lainnya untuk kategori C...
        ];

        foreach ($datas as $data) {
            Nilaipph::create($data);
        }
    }
}
