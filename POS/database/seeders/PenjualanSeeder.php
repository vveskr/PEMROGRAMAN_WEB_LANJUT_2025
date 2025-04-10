<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjualans = [];
        for ($i = 1; $i <= 10; $i++) {
            $penjualans[] = [
                'user_id' => 2,
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'TRX00' . $i,
                'penjualan_tanggal' => Carbon::now(),
            ];
        }
        DB::table('t_penjualan')->insert($penjualans);
    }
}