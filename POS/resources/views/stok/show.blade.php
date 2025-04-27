<div class="card">
    <div class="card-header">
        <h4>Detail Stok</h4>
    </div>
    <div class="card-body">
        <p><strong>Barang:</strong> {{ $stok->barang->barang_nama }}</p>
        <p><strong>Jumlah:</strong> {{ $stok->stok_jumlah }}</p>
        <p><strong>Keterangan:</strong> {{ $stok->stok_keterangan }}</p>
        <p><strong>Waktu:</strong> {{ $stok->created_at->format('d-m-Y H:i') }}</p>
    </div>
</div>