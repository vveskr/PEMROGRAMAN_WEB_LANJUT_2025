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
        $data = [
            ['user_id' => 1, 'pembeli' => 'Andi', 'penjualan_kode' => 'TRX0001', 'penjualan_tanggal' => Carbon::now()->subDays(2)],
            ['user_id' => 2, 'pembeli' => 'Budi', 'penjualan_kode' => 'TRX0002', 'penjualan_tanggal' => Carbon::now()->subDays(4)],
            ['user_id' => 3, 'pembeli' => 'Citra', 'penjualan_kode' => 'TRX0003', 'penjualan_tanggal' => Carbon::now()->subDays(6)],
            ['user_id' => 1, 'pembeli' => 'Dewi', 'penjualan_kode' => 'TRX0004', 'penjualan_tanggal' => Carbon::now()->subDays(1)],
            ['user_id' => 2, 'pembeli' => 'Eka', 'penjualan_kode' => 'TRX0005', 'penjualan_tanggal' => Carbon::now()->subDays(3)],
            ['user_id' => 3, 'pembeli' => 'Fajar', 'penjualan_kode' => 'TRX0006', 'penjualan_tanggal' => Carbon::now()->subDays(5)],
            ['user_id' => 1, 'pembeli' => 'Gita', 'penjualan_kode' => 'TRX0007', 'penjualan_tanggal' => Carbon::now()->subDays(7)],
            ['user_id' => 2, 'pembeli' => 'Hadi', 'penjualan_kode' => 'TRX0008', 'penjualan_tanggal' => Carbon::now()->subDays(9)],
            ['user_id' => 3, 'pembeli' => 'Indah', 'penjualan_kode' => 'TRX0009', 'penjualan_tanggal' => Carbon::now()->subDays(11)],
            ['user_id' => 1, 'pembeli' => 'Joko', 'penjualan_kode' => 'TRX0010', 'penjualan_tanggal' => Carbon::now()->subDays(13)],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}