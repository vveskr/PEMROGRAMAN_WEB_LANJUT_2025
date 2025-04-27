@if(!$penjualan)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"></h5>
            <button type="button" class="close text-white" data-dismiss="modal">
                <span>×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="receipt-container">
                <div class="receipt-header text-center mb-4">
                    <h3 class="mb-1">NITIPDONG.NA STORE</h3>
                    <p class="mb-0">Jl. Soekarno-Hatta No.9, Malang</p>
                    <p class="mb-0">Telp: (021) 1235489674</p>
                </div>
                
                <div class="receipt-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Kode Transaksi:</strong> {{ $penjualan->penjualan_kode }}
                        </div>
                        <div class="col-6 text-right">
                            <strong>Tanggal:</strong> {{ $penjualan->penjualan_tanggal->translatedFormat('d M Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Kasir:</strong> {{ $penjualan->user->nama ?? 'System' }}
                        </div>
                        <div class="col-12">
                            <strong>Pembeli:</strong> {{ $penjualan->pembeli }}
                        </div>
                    </div>

                    <table class="table receipt-table">
                        <thead class="bg-light">
                            <tr>
                                <th>Barang</th>
                                <th class="text-right">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->details as $detail)
                            <tr>
                                <td>{{ $detail->barang->barang_nama ?? '-' }}</td>
                                <td class="text-right">@currency($detail->harga)</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-right">@currency($detail->harga * $detail->jumlah)</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="3" class="text-right">TOTAL</th>
                                <th class="text-right">@currency($penjualan->details->sum(fn($d) => $d->harga * $d->jumlah))</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="receipt-footer text-center mt-4">
                    <p class="mb-1">Terima kasih telah berbelanja</p>
                    <p class="mb-0">Sehat selalu kakak-kakak!</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-success" onclick="printReceipt()">
                <i class="fas fa-print"></i> Cetak Struk
            </button>
        </div>
    </div>
</div>
@endif

<style>
    .receipt-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 2px solid #000;
    }

    .receipt-header h3 {
        font-size: 24px;
        font-weight: bold;
    }

    .receipt-table {
        width: 100%;
        margin: 15px 0;
    }

    .receipt-table th,
    .receipt-table td {
        padding: 8px;
        border-top: none;
        border-bottom: 1px solid #dee2e6;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        
        .modal-content,
        .modal-content * {
            visibility: visible;
        }

        .modal-content {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 20px;
            box-shadow: none;
            border: none;
        }

        .modal-footer {
            display: none;
        }

        .receipt-container {
            border: none;
            padding: 0;
        }
    }
</style>

<script>
    function printReceipt() {
        window.print();
    }
</script>