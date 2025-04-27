<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Konfirmasi Hapus</h5>
            <button type="button" class="close text-white" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
            <ul class="list-unstyled">
                <li><strong>Kode:</strong> {{ $penjualan->penjualan_kode }}</li>
                <li><strong>Pembeli:</strong> {{ $penjualan->pembeli }}</li>
                <li><strong>Tanggal:</strong> {{ $penjualan->penjualan_tanggal->translatedFormat('d M Y H:i') }}</li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <form id="deleteForm" action="{{ route('penjualan.delete_ajax', $penjualan->penjualan_id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle form submission
    $('#deleteForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'DELETE',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire('Sukses!', response.message, 'success');
                    tablePenjualan.ajax.reload(); // Reload datatable
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan teknis', 'error');
            }
        });
    });
</script>