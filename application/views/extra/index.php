<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-plus-square me-2"></i>Produk Extra</h2>
        <button class="btn btn-primary" onclick="showModalTambah()">
            <i class="fas fa-plus me-1"></i> Tambah Extra
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="search" class="form-control" placeholder="🔍 Cari Nama Extra...">
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark text-light">
                <tr>
                    <th>Nama Extra</th>
                    <th>SKU</th>
                    <th>Satuan</th>
                    <th>HPP</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="extra_list"></tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalExtra" tabindex="-1" aria-labelledby="modalExtraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formExtra">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalExtraLabel">Tambah Extra</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="extra_id" name="id">
                    <div class="mb-3"><label>Nama Extra*</label><input type="text" class="form-control"
                            name="nama_extra" id="nama_extra" required></div>
                    <div class="mb-3"><label>SKU*</label><input type="text" class="form-control" name="sku" id="sku"
                            required></div>
                    <div class="mb-3"><label>Satuan*</label><input type="text" class="form-control" name="satuan"
                            id="satuan" required></div>
                    <div class="mb-3"><label>HPP</label><input type="number" class="form-control" name="hpp" id="hpp">
                    </div>
                    <div class="mb-3">
                        <label>Harga Jual*</label>
                        <input type="number" class="form-control" name="harga" id="harga" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
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
    function loadExtra(page = 1) {
        const search = $("#search").val();
        $.get("<?= site_url('extra/load_data') ?>", {
            page: page,
            search: search
        }, function(res) {
            let html = "";
            $.each(res.extra, function(i, e) {
                html += `
                    <tr>
                        <td>${e.nama_extra}</td>
                        <td>${e.sku}</td>
                        <td>${e.satuan}</td>
                        <td class="text-end">Rp ${parseInt(e.hpp).toLocaleString('id-ID')}</td>
                        <td class="text-end">Rp ${parseInt(e.harga).toLocaleString('id-ID')}</td>
                        <td>
                            ${e.status === 'aktif' 
                                ? '<span class="badge bg-success">Aktif</span>' 
                                : '<span class="badge bg-secondary">Nonaktif</span>'
                            }
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${e.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${e.id}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `;
            });
            $("#extra_list").html(html);
            $("#pagination").html(res.pagination);
        }, "json");
    }

    // Load pertama
    loadExtra();

    // Pencarian AJAX
    $("#search").on("input", function() {
        loadExtra(1);
    });

    // Pagination
    $(document).on("click", ".page-link", function(e) {
        e.preventDefault();
        const page = $(this).attr("data-ci-pagination-page");
        if (page) loadExtra(page);
    });


    // Tambah/Edit submit
    $("#formExtra").submit(function(e) {
        e.preventDefault();
        $.post("<?= site_url('extra/save') ?>", $(this).serialize(), function(res) {
            alert(res.message || "Data berhasil disimpan!");
            if (res.status === "success") {
                $("#modalExtra").modal("hide");
                loadExtra();
            }
        }, 'json');
    });

    // Edit button
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");
        $.get("<?= site_url('extra/get/') ?>" + id, function(res) {
            if (res.status === "success") {
                showModalEdit(res.data);
            } else {
                alert(res.message || "Gagal memuat data.");
            }
        }, 'json');

    });

    // Delete
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        if (confirm("Yakin ingin menghapus data ini?")) {
            $.post("<?= site_url('extra/delete') ?>", {
                id
            }, function(res) {
                alert(res.message || "Data berhasil dihapus!");
                loadExtra();
            }, 'json');
        }
    });

    window.showModalTambah = function() {
        $("#formExtra")[0].reset();
        $("#extra_id").val("");
        $("#modalExtraLabel").text("Tambah Extra");
        $("#modalExtra").modal("show");
    };

    window.showModalEdit = function(data) {
        $("#extra_id").val(data.id);
        $("#nama_extra").val(data.nama_extra);
        $("#sku").val(data.sku);
        $("#satuan").val(data.satuan);
        $("#hpp").val(data.hpp);
        $("#harga").val(data.harga);
        $("#status").val(data.status);
        $("#modalExtraLabel").text("Edit Extra");
        $("#modalExtra").modal("show");
    };
});
</script>