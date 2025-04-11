@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @empty($supplier)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @else
            <form method="POST" action="{{ url('/supplier/'.$supplier->supplier_id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" class="form-control" id="supplier_kode" name="supplier_kode" value="{{ old('supplier_kode', $supplier->supplier_kode) }}" required>
                    @error('supplier_kode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" class="form-control" id="supplier_nama" name="supplier_nama" value="{{ old('supplier_nama', $supplier->supplier_nama) }}" required>
                    @error('supplier_nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Alamat Supplier</label>
                    <input type="text" class="form-control" id="supplier_alamat" name="supplier_alamat" value="{{ old('supplier_alamat', $supplier->supplier_alamat) }}" required>
                    @error('supplier_alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a class="btn btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
            </form>
        @endempty
    </div>
</div>
@endsection
