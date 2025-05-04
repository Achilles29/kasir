<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-sitemap me-2"></i> Daftar Divisi</h2>
        <button class="btn btn-primary" onclick="showModalTambah()">
            <i class="fas fa-plus me-1"></i> Tambah Divisi
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="search" class="form-control" placeholder="🔍 Cari Nama Divisi...">
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark text-light">
                <tr>
                    <th>Nama Divisi</th>
                    <th>Urutan</th>
                    <th>Jumlah Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="divisi_list"></tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalDivisi" tabindex="-1" aria-labelledby="modalDivisiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formDivisi">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalDivisiLabel">Tambah Divisi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="divisi_id">
                    <div class="mb-3">
                        <label>Nama Divisi*</label>
                        <input type="text" name="nama_divisi" id="nama_divisi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Urutan Tampilan*</label>
                        <input type="number" name="urutan_tampilan" id="urutan_tampilan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    function loadDivisi(page = 1) {
        const search = $('#search').val();
        $.get("<?= site_url('divisi/load_data') ?>", {
            page: page,
            search: search
        }, function(res) {
            let html = '';
            $.each(res.data, function(i, d) {
                html += `
                    <tr>
                        <td class="text-start">${d.nama_divisi}</td>
                        <td>${d.urutan_tampilan}</td>
                        <td>${d.jumlah_kategori}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" data-id="${d.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${d.id}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`;
            });
            $('#divisi_list').html(html);
            $('#pagination').html(res.pagination);
        }, 'json');
    }

    // Inisialisasi
    loadDivisi();

    $('#search').on('input', function() {
        loadDivisi(1);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).attr("data-ci-pagination-page");
        if (page) loadDivisi(page);
    });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data("id");
        $.get("<?= site_url('divisi/get/') ?>" + id, function(res) {
            if (res.status === "success") {
                showModalEdit(res.data);
            } else {
                alert("Data tidak ditemukan");
            }
        }, 'json');
    });


    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data("id");
        if (confirm("Yakin ingin menghapus divisi ini?")) {
            $.post("<?= site_url('divisi/delete') ?>", {
                id
            }, function(res) {
                alert(res.message);
                loadDivisi();
            }, 'json');
        }
    });

    $('#formDivisi').submit(function(e) {
        e.preventDefault();
        $.post("<?= site_url('divisi/save') ?>", $(this).serialize(), function(res) {
            if (res.status === 'success') {
                $('#modalDivisi').modal('hide');
                loadDivisi();
            }
            alert(res.message);
        }, 'json');
    });

    window.showModalTambah = function() {
        $('#formDivisi')[0].reset();
        $('#divisi_id').val('');
        $('#modalDivisiLabel').text("Tambah Divisi");
        $('#modalDivisi').modal('show');
    };

    window.showModalEdit = function(data) {
        $('#modalDivisiLabel').text("Edit Divisi");
        $('#divisi_id').val(data.id);
        $('#nama_divisi').val(data.nama_divisi);
        $('#urutan_tampilan').val(data.urutan_tampilan);
        $('#modalDivisi').modal('show');
    };
});
</script>