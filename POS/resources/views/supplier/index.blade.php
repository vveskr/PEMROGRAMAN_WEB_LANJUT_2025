@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
            <button class="btn btn-sm btn-success mt-1" data-url="{{ url('/supplier/create_ajax') }}" onclick="modalAction(this.getAttribute('data-url'))">Tambah Ajax</button>
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
                    <label for="supplier_id" class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select name="supplier_id" id="supplier_id" class="form-control">
                            <option value="">- Semua -</option>
                            @foreach ($data_supplier as $item)
                                <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Supplier</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-sm" id="table_supplier">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat Supplier</th>
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
            var dataSupplier = $('#table_supplier').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('supplier/list') }}",
                type: "POST",
                data: function (d) {
                    d.supplier_id = $('#supplier_id').val(); // kirim data filter
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "supplier_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_alamat",
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

    $('#supplier_id').on('change', function() {
        dataSupplier.ajax.reload(); // reload data saat filter berubah
    });

</script>
@endpush