@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('barang') }}">
            @csrf

            <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" id="kategori_id" name="kategori_id" required>
                    <option value="">- Pilih Kategori -</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" class="form-control" id="barang_kode" name="barang_kode" value="{{ old('barang_kode') }}" required>
                @error('barang_kode')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" class="form-control" id="barang_nama" name="barang_nama" value="{{ old('barang_nama') }}" required>
                @error('barang_nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Harga Beli</label>
                <input type="text" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" required>
                @error('harga_beli')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Harga Jual</label>
                <input type="text" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required>
                @error('harga_jual')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ url('barang') }}" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
@endsection
