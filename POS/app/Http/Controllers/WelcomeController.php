<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\SupplierModel;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';

        // Data statistik untuk dashboard
        $jumlah_user = User::count();
        $jumlah_supplier = SupplierModel::count();
        $jumlah_kategori = KategoriModel::count();
        $jumlah_barang = BarangModel::count();

        $pengguna_terbaru = User::latest()->take(5)->get();
        $barang_terbaru = BarangModel::latest()->take(5)->get();

        return view('welcome', compact(
            'breadcrumb',
            'activeMenu',
            'jumlah_user',
            'jumlah_supplier',
            'jumlah_kategori',
            'jumlah_barang',
            'pengguna_terbaru',
            'barang_terbaru'
        ));
    }
}
