@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
            <button class="btn btn-sm btn-success mt-1" data-url="{{ url('/kategori/create_ajax') }}" onclick="modalAction(this.getAttribute('data-url'))">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="kategori_id" class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select name="kategori_id" id="kategori_id" class="form-control">
                            <option value="">- Semua -</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Produk</small>
                    </div>
                </div>
            </div>
        </div>       
        <table class="table table-bordered table-hover table-sm" id="table_kategori">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori Produk</th>
                    <th>Nama Kategori Produk</th>
                    <th>Slug Kategori Produk</th>
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
    $(document).ready(function() {
        var dataLevel = $('#table_kategori').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('kategori/list') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "kategori_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_slug",
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