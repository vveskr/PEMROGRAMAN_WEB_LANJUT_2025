<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return KategoriModel::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|unique:m_kategori,kategori_kode|max:10',
            'kategori_nama' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriModel $kategori)
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'sometimes|required|unique:m_kategori,kategori_kode,' . $kategori->kategori_id . ',kategori_id|max:10',
            'kategori_nama' => 'sometimes|required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategoriData = [];
        if ($request->filled('kategori_kode')) {
            $kategoriData['kategori_kode'] = $request->kategori_kode;
        }
        if ($request->filled('kategori_nama')) {
            $kategoriData['kategori_nama'] = $request->kategori_nama;
        }

        $kategori->update($kategoriData);

        return response()->json($kategori);
    }

    public function destroy(KategoriModel $kategori)
    {
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
