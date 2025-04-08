<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = [
        ['supplier_id' => 1, 'supplier_kode' => 'SUP001', 'supplier_nama' => 'Supplier A', 'alamat_supplier' => 'Jl.Raya No.123', 'created_at' => now(), 'updated_at' => now()],
        ['supplier_id' => 2, 'supplier_kode' => 'SUP002', 'supplier_nama' => 'Supplier B', 'alamat_supplier' => 'Jl.Bromo No.789', 'created_at' => now(), 'updated_at' => now()],
        ['supplier_id' => 3, 'supplier_kode' => 'SUP003', 'supplier_nama' => 'Supplier C', 'alamat_supplier' => 'Jl.Cokroaminoto No.34', 'created_at' => now(), 'updated_at' => now()],
        ['supplier_id' => 4, 'supplier_kode' => 'SUP004', 'supplier_nama' => 'Supplier D', 'alamat_supplier' => 'Jl.Soedirman No.56', 'created_at' => now(), 'updated_at' => now()],  
        ];   

        DB::table('m_supplier')->insert($supplier);
    }
}