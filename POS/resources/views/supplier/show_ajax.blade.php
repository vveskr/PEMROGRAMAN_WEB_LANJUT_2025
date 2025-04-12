<div class="modal-dialog modal-md" role="document" id="modal-master">
    <div class="modal-content">
        @if (!$supplier)
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Data Tidak Ditemukan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Data supplier tidak ditemukan.
                </div>
            </div>
        @else
            <div class="modal-header bg-info">
                <h5 class="modal-title">Detail Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Kode Supplier</th>
                        <td>{{ $supplier->supplier_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Supplier</th>
                        <td>{{ $supplier->supplier_nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $supplier->supplier_alamat }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
            </div>
        @endif
    </div>
</div>