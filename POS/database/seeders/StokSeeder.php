<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stoks = [];
        for ($i = 1; $i <= 10; $i++) {
            $stoks[] = [
                'barang_id' => $i,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => rand(50, 200),
            ];
        }
        DB::table('t_stok')->insert($stoks);
    }
}