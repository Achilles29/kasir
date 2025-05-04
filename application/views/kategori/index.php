<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-tags me-2"></i>Daftar Kategori</h2>
        <button class="btn btn-primary" onclick="showModalTambah()">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-2">
            <select id="per_page" class="form-select">
                <option value="10">Tampilkan 10</option>
                <option value="30">Tampilkan 30</option>
                <option value="50">Tampilkan 50</option>
                <option value="100">Tampilkan 100</option>
                <option value="1000000">Tampilkan Semua</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="🔍 Cari Nama Kategori...">
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark text-light">
                <tr>
                    <th>Nama Kategori</th>
                    <th>Urutan</th>
                    <th>Jumlah Produk</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori_list"></tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>

</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formKategori">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalKategoriLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="kategori_id">
                    <div class="mb-3">
                        <label>Nama Kategori*</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Urutan Tampilan*</label>
                        <input type="number" name="urutan" id="urutan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Divisi*</label>
                        <select name="pr_divisi_id" id="pr_divisi_id" class="form-select" required>
                            <option value="">-- Pilih Divisi --</option>
                            <?php foreach ($this->Divisi_model->get_all_divisi() as $d): ?>
                            <option value="<?= $d['id']; ?>"><?= $d['nama_divisi']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Tampil</option>
                            <option value="0">Tidak Tampil</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    function loadKategori(page = 1) {
        const search = $('#search').val();
        const per_page = $('#per_page').val();

        $.get("<?= site_url('kategori/load_data') ?>", {
            page: page,
            search: search,
            per_page: per_page
        }, function(res) {
            let html = '';
            $.each(res.data, function(i, k) {
                html += `
                <tr>
                    <td class="text-start">${k.nama_kategori}</td>
                    <td>${k.urutan}</td>
                    <td>${k.jumlah_produk}</td>
                    <td>${k.nama_divisi}</td>
                    <td>${k.status == 1 
                        ? '<span class="badge bg-success">Tampil</span>' 
                        : '<span class="badge bg-secondary">Tidak Tampil</span>'}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-edit" data-id="${k.id}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${k.id}"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>`;
            });
            $('#kategori_list').html(html);
            $('#pagination').html(res.pagination);
        }, 'json');
    }
    $('#per_page').on('change', function() {
        loadKategori(1);
    });


    loadKategori();

    $('#search').on('input', function() {
        loadKategori(1);
    });

    // PAGINATION klik
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) {
            loadKategori(page);
        }
    });





    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data("id");
        $.get("<?= site_url('kategori/get/') ?>" + id, function(res) {
            if (res.status === "success") {
                showModalEdit(res.data);
            } else {
                alert("Data tidak ditemukan");
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data("id");
        if (confirm("Yakin ingin menghapus kategori ini?")) {
            $.post("<?= site_url('kategori/delete') ?>", {
                id
            }, function(res) {
                alert(res.message);
                loadKategori();
            }, 'json');
        }
    });

    $('#formKategori').submit(function(e) {
        e.preventDefault();
        $.post("<?= site_url('kategori/save') ?>", $(this).serialize(), function(res) {
            if (res.status === 'success') {
                $('#modalKategori').modal('hide');
                loadKategori();
            }
            alert(res.message);
        }, 'json');
    });

    window.showModalTambah = function() {
        $('#formKategori')[0].reset();
        $('#kategori_id').val('');
        $('#modalKategoriLabel').text("Tambah Kategori");
        $('#modalKategori').modal('show');
    };

    window.showModalEdit = function(data) {
        $('#modalKategoriLabel').text("Edit Kategori");
        $('#kategori_id').val(data.id);
        $('#nama_kategori').val(data.nama_kategori);
        $('#urutan').val(data.urutan);
        $('#pr_divisi_id').val(data.pr_divisi_id);
        $('#status').val(data.status);
        $('#modalKategori').modal('show');
    };
});
</script>