@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Level</button>
                <a href="{{ url('level/create') }}" class="btn btn-primary">Tambah</a>
                <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-hover table-sm" id="table_level">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
</div>
@endsection

@push('css')
<!-- Tambahkan custom CSS di sini jika diperlukan -->
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataLevel;
    $(document).ready(function() {
        var dataLevel = $('#table_level').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('level/list') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "level_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "level_name",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush