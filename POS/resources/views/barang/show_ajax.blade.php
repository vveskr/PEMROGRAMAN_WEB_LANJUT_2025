@if (!$barang)
    <div class="modal-dialog modal-md" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Data barang tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-md" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Detail Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th style="width: 35%;">Kode Barang</th>
                        <td>{{ $barang->barang_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>{{ $barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $barang->kategori ? $barang->kategori->kategori_nama : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
            </div>
        </div>
    </div>
@endif