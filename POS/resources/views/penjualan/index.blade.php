@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Transaksi Penjualan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ route('penjualan.create_ajax') }}')" class="btn btn-success">
                    <i class="fas fa-cash-register"></i> Transaksi Baru
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-penjualan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Pembeli</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection {{-- Pastikan @section ditutup dengan benar --}}

@push('js') {{-- Pastikan @push('js') ada --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        function showDetail(url) {
            $.get(url, function (data) {
                let html = `
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Transaksi</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Harga</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                data.details.forEach(detail => {
                    html += `
                            <tr>
                                <td>${detail.barang.barang_nama}</td>
                                <td>Rp ${Number(detail.harga).toLocaleString()}</td>
                                <td>${detail.jumlah}</td>
                                <td>Rp ${(detail.harga * detail.jumlah).toLocaleString()}</td>
                            </tr>`;
                });

                html += `</tbody></table></div></div></div>`;

                $('#myModal').html(html).modal('show');
            });
        }

        var tablePenjualan;
        $(document).ready(function () {
            tablePenjualan = $('#table-penjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('penjualan.list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        className: "text-center",
                        width: "5%",
                        orderable: false
                    },
                    {
                        data: "penjualan_kode",
                        name: "penjualan_kode",
                        className: "text-center",
                        width: "15%"
                    },
                    {
                        data: "pembeli",
                        name: "pembeli",
                        className: "text-left",
                        width: "15%"
                    },
                    {
                        data: "penjualan_tanggal",
                        name: "penjualan_tanggal",
                        className: "text-center",
                        width: "15%"
                    },
                    {
                        data: "total",
                        name: "total",
                        className: "text-right",
                        width: "15%",
                        render: function (data) {
                            return `<span class="font-weight-bold">${data}</span>`;
                        }
                    },
                    {
                        data: "nama",
                        name: "user.nama",
                        className: "text-center",
                        width: "15%"
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        className: "text-center",
                        width: "20%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-penjualan_filter input').unbind().bind().on('keyup', function (e) {
                if (e.keyCode == 13) {
                    tablePenjualan.search(this.value).draw();
                }
            });
        });
    </script>
@endpush