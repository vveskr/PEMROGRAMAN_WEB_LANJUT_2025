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
        'barang_kode' => 'required|unique:m_barang,barang_kode',
        'barang_nama' => 'required',
        'kategori_id' => 'required|exists:m_kategori,kategori_id',
        'harga_beli' => 'required|numeric',
        'harga_jual' => 'required|numeric|gt:harga_beli',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Upload gambar
    $image = $request->file('image');
    $imageName = $image->hashName();
    $image->store('public/barang'); // disimpan di storage/app/public/barang

    // Create barang
    $barang = BarangModel::create([
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'kategori_id' => $request->kategori_id,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
        'image' => $imageName,
    ]);

    return response()->json($barang, 201);
}

public function update(Request $request, BarangModel $barang)
{
    $validator = Validator::make($request->all(), [
        'barang_kode' => 'sometimes|required|unique:m_barang,barang_kode,'.$barang->barang_id.',barang_id',
        'barang_nama' => 'sometimes|required',
        'kategori_id' => 'sometimes|required|exists:m_kategori,kategori_id',
        'harga_beli' => 'sometimes|required|numeric',
        'harga_jual' => 'sometimes|required|numeric|gt:harga_beli',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $data = $request->only(['barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual']);

    if ($request->hasFile('image')) {
        // Upload gambar baru
        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->store('public/barang');
        $data['image'] = $imageName;
    }

    $barang->update($data);

    return response()->json($barang);
}

    public function show(BarangModel $barang)
    {
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