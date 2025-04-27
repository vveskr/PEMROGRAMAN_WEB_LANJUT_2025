<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel; // Pastikan ini sesuai dengan nama model Anda
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json(BarangModel::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'required|unique:m_barang,barang_kode', // Sesuaikan dengan nama tabel dan kolom
            'barang_nama' => 'required',
            'kategori_id' => 'required|exists:m_kategori,kategori_id', // Pastikan kategori_id ada di tabel m_kategori
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric|gt:harga_beli', // Pastikan harga jual lebih besar dari harga beli
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'sometimes|required|unique:m_barang,barang_kode,'.$barang->barang_id.',barang_id', // Sesuaikan nama tabel dan kolom primary key
            'barang_nama' => 'sometimes|required',
            'kategori_id' => 'sometimes|required|exists:m_kategori,kategori_id', // Pastikan kategori_id ada di tabel m_kategori
            'harga_beli' => 'sometimes|required|numeric',
            'harga_jual' => 'sometimes|required|numeric|gt:harga_beli', // Pastikan harga jual lebih besar dari harga beli
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $barang->update($request->all());
        return response()->json($barang);
    }
    
    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus',
        ]);
    }
}