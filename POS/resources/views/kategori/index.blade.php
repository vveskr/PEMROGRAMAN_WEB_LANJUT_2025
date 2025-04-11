@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah Kategori</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {!! $dataTable->table(['class' => 'table table-bordered table-hover table-sm']) !!}
    </div>
</div>
@endsection

@push('css')
{{-- Optional custom styles --}}
@endpush

@push('js')
{!! $dataTable->scripts() !!}
@endpush
