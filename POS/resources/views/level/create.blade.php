@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/level') }}">
            @csrf

            <div class="form-group">
                <label>Kode Level</label>
                <input type="text" class="form-control" name="level_kode" value="{{ old('level_kode') }}" required>
                @error('level_kode')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Level</label>
                <input type="text" class="form-control" name="level_nama" value="{{ old('level_nama') }}" required>
                @error('level_nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ url('/level') }}" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
@endsection
