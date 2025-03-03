<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'stok_id' => $i,
                'barang_id' => rand(1, 10),
                'user_id' => rand(1, 2),
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(10, 50),
            ];
        }
        DB::table('t_stok')->insert($data);
    }
}