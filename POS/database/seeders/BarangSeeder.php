<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [];
        for ($i = 1; $i <= 10; $i++) {
            $barangs[] = [
                'kategori_id' => rand(1, 4),
                'barang_kode' => 'B00' . $i,
                'barang_nama' => 'Barang ' . $i,
                'harga_beli' => rand(5000, 20000),
                'harga_jual' => rand(10000, 25000),
            ];
        }
        DB::table('m_barang')->insert($barangs);

    }
}