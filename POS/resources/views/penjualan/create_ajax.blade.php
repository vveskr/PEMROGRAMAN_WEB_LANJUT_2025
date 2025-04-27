<form action="{{ route('penjualan.store_ajax') }}" method="POST" id="form-transaksi">
    @csrf
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-cash-register mr-2"></i>Transaksi Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Section Pembeli -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Data Pembeli</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nama Pembeli<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="pembeli" class="form-control"
                                    placeholder="Masukkan nama pembeli" required>
                                <small class="form-text text-muted">Contoh: Pelanggan 1 / Konsumen Umum</small>
                                <small class="text-danger" id="error-pembeli"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Barang -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i>Tambah Barang</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Pilih Barang<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select id="select-barang" class="form-control select2">
                                    <option value="">- Pilih Barang -</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}"
                                            data-stok="{{ $b->stok ? $b->stok->stok_jumlah : 0 }}"
                                            data-nama="{{ $b->barang_nama }}">
                                            {{ $b->barang_nama }}
                                            (Rp {{ number_format($b->harga_jual, 0, ',', '.') }})
                                            @if($b->stok)
                                                - Stok: {{ $b->stok->stok_jumlah }}
                                            @else
                                                - Stok: 0
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Ketik nama barang untuk mencari</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                                <i class="fas fa-plus-circle mr-1"></i>Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Daftar Item -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i>Keranjang Belanja</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="35%">Nama Barang</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="15%">Qty</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-list" class="small"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="alert alert-info mt-4 p-3">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h4 class="mb-0">Total: <span id="total" class="font-weight-bold">Rp 0</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#select-barang').select2({
            dropdownParent: $('#myModal')
        });
    });

    let items = [];

    function addItem() {
        const select = document.getElementById('select-barang');
        const selectedOption = select.options[select.selectedIndex];
        const stok = parseInt(selectedOption.dataset.stok) || 0;

        if (!selectedOption.value) {
            Swal.fire('Peringatan', 'Pilih barang terlebih dahulu!', 'warning');
            return;
        }

        if (stok < 1) {
            Swal.fire('Error', 'Stok barang habis!', 'error');
            return;
        }

        // Cek apakah barang sudah ada di keranjang
        const existingItem = items.find(item => item.barang_id === selectedOption.value);
        if (existingItem) {
            Swal.fire('Peringatan', 'Barang sudah ada di keranjang!', 'warning');
            return;
        }

        const item = {
            barang_id: selectedOption.value,
            nama: selectedOption.dataset.nama,
            harga: parseInt(selectedOption.dataset.harga),
            jumlah: 1
        };

        items.push(item);
        renderItems();
        calculateTotal();
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
        calculateTotal();
    }

    function renderItems() {
        const tbody = document.getElementById('items-list');
        tbody.innerHTML = '';

        items.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            tbody.innerHTML += `
            <tr>
                <td>
                    ${item.nama}
                    <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                </td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>
                    <input type="number" name="items[${index}][jumlah]"
                           value="${item.jumlah}" min="1"
                           class="form-control form-control-sm"
                           onchange="updateQty(${index}, this.value)">
                </td>
                <td>Rp ${subtotal.toLocaleString()}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        });
    }

    function updateQty(index, value) {
        // Validasi jumlah tidak boleh melebihi stok
        const maxStok = parseInt($('#select-barang option[value="' + items[index].barang_id + '"]').data('stok')) || 0;

        if (parseInt(value) > maxStok) {
            Swal.fire('Error', 'Jumlah melebihi stok tersedia!', 'error');
            value = items[index].jumlah; // Kembalikan ke nilai sebelumnya
        }

        // Update jumlah di array items
        items[index].jumlah = parseInt(value) || 1;

        // Render ulang item dan hitung total
        renderItems();
        calculateTotal();
    }

    function calculateTotal() {
        const total = items.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString();
    }

    $("#form-transaksi").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire('Sukses', response.message, 'success');
                    tablePenjualan.ajax.reload();
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                    // Tampilkan error validasi
                    $('.text-danger').text('');
                    $.each(response.errors, function (field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Terjadi kesalahan teknis', 'error');
            }
        });
    });
</script>