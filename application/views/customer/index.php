<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h4><i class="fas fa-users me-2"></i>Daftar Pelanggan</h4>
        <button class="btn btn-primary" onclick="showModalTambah()">
            <i class="fas fa-plus me-1"></i> Tambah Pelanggan
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-2">
            <select id="per_page" class="form-select">
                <option value="10">Tampilkan 10</option>
                <option value="25">Tampilkan 25</option>
                <option value="50">Tampilkan 50</option>
                <option value="100">Tampilkan 100</option>
                <option value="1000000">Semua</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="🔍 Cari nama, telepon, atau kode...">
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Kode</th>
                    <th>JK</th>
                    <th>Tanggal Lahir</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="customer_list"></tbody>
        </table>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        <ul class="pagination" id="pagination"></ul>
    </div>
</div>

<!-- MODAL Pelanggan -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="formCustomer">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="customer_id">
                    <div class="mb-3"><label>Nama</label><input type="text" name="nama" id="nama" class="form-control"
                            required></div>
                    <div class="mb-3"><label>Kode Pelanggan</label><input type="text" id="kode_pelanggan"
                            class="form-control" disabled></div>
                    <div class="mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir"
                            id="tanggal_lahir" class="form-control"></div>
                    <div class="mb-3"><label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                            <option value="">- Pilih -</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Telepon</label><input type="text" name="telepon" id="telepon"
                            class="form-control"></div>
                    <div class="mb-3"><label>Email</label><input type="email" name="email" id="email"
                            class="form-control"></div>
                    <div class="mb-3"><label>Alamat</label><textarea name="alamat" id="alamat"
                            class="form-control"></textarea></div>
                    <div class="mb-3"><label>Foto</label><input type="file" name="foto" id="foto" class="form-control">
                    </div>
                    <img id="foto-preview" src="" width="100" style="display:none;" class="mb-2 rounded">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    function loadCustomer(page = 1) {
        const search = $("#search").val();
        const per_page = $("#per_page").val();

        $.get("<?= site_url('customer/load_data') ?>", {
            page,
            search,
            per_page
        }, function(res) {
            let html = '';
            $.each(res.data, function(i, c) {
                html += `
                <tr>
                    <td class="text-start">${c.nama}</td>
                    <td>${c.kode_pelanggan}</td>
                    <td>${c.jenis_kelamin || '-'}</td>
                    <td>${c.tanggal_lahir || '-'}</td>
                    <td>${c.email || '-'}</td>
                    <td>${c.telepon}</td>
                    <td>${c.alamat || '-'}</td>
                    <td>${c.total_poin}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                            <button class="btn btn-warning btn-sm btn-edit" data-id="${c.id}" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${c.id}" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            <a href="<?= site_url('customer/detail/') ?>${c.id}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-user"></i></a>
                            <a href="<?= site_url('customer/transaksi/') ?>${c.id}" class="btn btn-primary btn-sm" title="Riwayat Pembelian"><i class="fas fa-receipt"></i></a>
                            <a href="<?= site_url('customer/poin/') ?>${c.id}" class="btn btn-success btn-sm" title="Riwayat Poin"><i class="fas fa-star"></i></a>
                        </div>
                    </td>
                </tr>`;
            });

            $("#customer_list").html(html);
            $("#pagination").html(res.pagination);
        }, 'json');
    }

    loadCustomer();

    $("#search, #per_page").on("input change", () => loadCustomer(1));

    $(document).on("click", ".pagination .page-link", function(e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadCustomer(page);
    });


    // Tambah Pelanggan
    window.showModalTambah = function() {
        $('#formCustomer')[0].reset();
        $('#foto-preview').hide();
        $('#customer_id').val('');
        $('#customerModal .modal-title').text('Tambah Pelanggan');
        $('#customerModal').modal('show');
    };

    // Simpan (Tambah/Edit)
    $("#formCustomer").submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = $("#customer_id").val();
        const url = id ? "<?= site_url('customer/edit_customer/') ?>" + id :
            "<?= site_url('customer/add_customer') ?>";

        $.ajax({
            url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                const data = JSON.parse(res);
                if (data.status === 'success') {
                    $('#customerModal').modal('hide');
                    loadCustomer();
                } else {
                    alert("Gagal menyimpan data.");
                }
            }
        });
    });

    // Edit Pelanggan
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");
        $.get("<?= site_url('customer/get_customer/') ?>" + id, function(res) {
            const data = JSON.parse(res);
            $("#customer_id").val(data.id);
            $("#nama").val(data.nama);
            $("#telepon").val(data.telepon);
            $("#alamat").val(data.alamat);
            $("#email").val(data.email);
            $("#tanggal_lahir").val(data.tanggal_lahir);
            $("#jenis_kelamin").val(data.jenis_kelamin);
            $("#kode_pelanggan").val(data.kode_pelanggan);
            if (data.foto) {
                $("#foto-preview").attr("src", "<?= base_url('uploads/foto_pelanggan/') ?>" +
                    data.foto).show();
            }
            $('#customerModal .modal-title').text('Edit Pelanggan');
            $('#customerModal').modal('show');
        });
    });

    // Hapus Pelanggan
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        if (confirm("Yakin ingin menghapus pelanggan ini?")) {
            $.post("<?= site_url('customer/delete_customer/') ?>" + id, function(res) {
                const data = JSON.parse(res);
                if (data.status === 'success') {
                    loadCustomer();
                } else {
                    alert("Gagal menghapus pelanggan.");
                }
            });
        }
    });
});
</script>