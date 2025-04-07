<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Level Pengguna</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach($level as $item)
                            <option value="{{ $item->level_id }}">{{ $item->level_name }}</option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
    // Inisialisasi form validasi menggunakan jQuery Validation
    $("#form-tambah").validate({
        rules: {
            level_id: { required: true, number: true },
            username: { required: true, minlength: 3, maxlength: 20 },
            nama: { required: true, minlength: 3, maxlength: 100 },
            password: { required: true, minlength: 6, maxlength: 20 }
        },
        submitHandler: function(form) {
            // Lakukan AJAX submit jika validasi berhasil
            $.ajax({
                url: form.action,  // URL untuk mengirim data
                type: form.method, // Metode pengiriman (POST)
                data: $(form).serialize(),  // Ambil data dari form
                success: function(response) {
                    if (response.status) {
                        // Menutup modal jika berhasil
                        $('#myModal').modal('hide');
                        
                        // Menampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        
                        // Reload DataTable setelah berhasil
                        dataUser.ajax.reload();
                    } else {
                        // Mengosongkan pesan error sebelumnya
                        $('.error-text').text('');
                        
                        // Menampilkan pesan error pada setiap field yang invalid
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        
                        // Menampilkan pesan error umum
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Menampilkan pesan error jika terjadi masalah saat request AJAX
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Silakan coba lagi'
                    });
                }
            });
            return false;  // Prevent default form submission
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            // Penempatan error message
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            // Menambahkan kelas is-invalid pada element yang error
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            // Menghapus kelas is-invalid saat validasi berhasil
            $(element).removeClass('is-invalid');
        }
    });
});
</script>