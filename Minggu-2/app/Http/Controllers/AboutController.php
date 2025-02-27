<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __invoke(Request $request)
    {
        return "Nama: [Isi Nama] <br> NIM: [Isi NIM]";
    }
}
