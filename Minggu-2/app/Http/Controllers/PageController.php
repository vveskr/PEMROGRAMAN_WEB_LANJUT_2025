<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    //Method untuk halaman utama
    public function index()
    {
        return "Selamat Datang";
    }

    // Method untuk halaman about
    public function about()
    {
        return "Nama: [Isi Nama] <br> NIM: [Isi NIM]";
    }

    // Method untuk halaman artikel berdasarkan ID
    public function articles($id)
    {
        return "Halaman Artikel dengan Id {$id}";
    }
}
