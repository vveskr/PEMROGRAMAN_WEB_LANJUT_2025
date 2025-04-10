<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'FB', 'kategori_nama' => 'Food & Beverage', 'kategori_slug' => 'food-beverage'],
            ['kategori_kode' => 'BH', 'kategori_nama' => 'Beauty & Health', 'kategori_slug' => 'beauty-health'],
            ['kategori_kode' => 'HC', 'kategori_nama' => 'Home Care', 'kategori_slug' => 'home-care'],
            ['kategori_kode' => 'BK', 'kategori_nama' => 'Baby & Kid', 'kategori_slug' => 'baby-kid'],
        ];
        DB::table('m_kategori')->insert($data);
    }
}