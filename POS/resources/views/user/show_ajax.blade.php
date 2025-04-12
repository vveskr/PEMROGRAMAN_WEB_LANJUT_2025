@if (!$user)
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
                    <i class="fas fa-exclamation-triangle"></i> Data user tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-md" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Detail Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td>{{ $user->level ? $user->level->level_nama . ' (' . $user->level->level_kode . ')' : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
            </div>
        </div>
    </div>
@endif