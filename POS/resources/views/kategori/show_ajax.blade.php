@if (!$kategori)
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
                    <i class="fas fa-exclamation-triangle"></i> Data kategori tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-md" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Detail Data Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Kode Kategori</th>
                        <td>{{ $kategori->kategori_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kategori</th>
                        <td>{{ $kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th>Slug Kategori</th>
                        <td>{{ $kategori->kategori_slug }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
            </div>
        </div>
    </div>
@endif