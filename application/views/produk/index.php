<style>
.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.pagination .page-link {
    color: #007bff;
    border-radius: 4px;
}
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark"><i class="fas fa-boxes me-2"></i>Daftar Produk</h2>
        <button class="btn btn-primary shadow-sm" id="btnTambahProduk">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </button>

    </div>

    <!-- FILTER BAR -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" id="search_produk" class="form-control shadow-sm" placeholder="🔍 Cari Nama Produk...">
        </div>
        <div class="col-md-4">
            <select id="filter_kategori" class="form-select shadow-sm">
                <option value="">📂 Semua Kategori</option>
                <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select id="per_page" class="form-select shadow-sm">
                <option value="10">10 Baris</option>
                <option value="25">25 Baris</option>
                <option value="30" selected>30 Baris</option>
                <option value="50">50 Baris</option>
                <option value="all">Semua</option>
            </select>
        </div>
    </div>

    <!-- STATUS PILLS -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item"><a href="#" class="nav-link filter_status active" data-status=""><i
                    class="fas fa-layer-group me-1"></i> Semua</a></li>
        <li class="nav-item"><a href="#" class="nav-link filter_status" data-status="1"><i class="fas fa-eye me-1"></i>
                Tampil</a></li>
        <li class="nav-item"><a href="#" class="nav-link filter_status" data-status="2"><i
                    class="fas fa-eye-slash me-1"></i> Tidak Tampil</a></li>
    </ul>

    <!-- TABLE -->
    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-dark text-light">
                <tr>
                    <th>Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>HPP</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produk_list"></tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- Modal Tambah/Edit Produk -->
<div class="modal fade" id="modalProduk" tabindex="-1" aria-labelledby="modalProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formProduk" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalProdukLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="produk-id">
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk*</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama_produk" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SKU*</label>
                        <input type="text" class="form-control" name="sku" id="sku" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan*</label>
                        <input type="text" class="form-control" name="satuan" id="satuan" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual*</label>
                        <input type="number" class="form-control" name="harga_jual" id="harga_jual" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">HPP</label>
                        <input type="number" class="form-control" name="hpp" id="hpp">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori Produk*</label>
                        <select class="form-select" name="kategori_id" id="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tampilkan di Menu</label>
                        <select class="form-select" name="tampil" id="tampil">
                            <option value="1">Ya</option>
                            <option value="2">Tidak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monitor Persediaan</label>
                        <select class="form-select" name="monitor_persediaan" id="monitor_persediaan">
                            <option value="1">Ya</option>
                            <option value="2">Tidak</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Foto Produk</label>
                        <input type="file" class="form-control" name="foto" id="foto">
                        <div id="previewFoto" class="mt-2"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- CUSTOM SCRIPT -->
<script>
$(document).ready(function() {
    function loadProduk(page = 1) {
        let kategori_id = $("#filter_kategori").val();
        let status = $(".filter_status.active").data("status");
        let search = $("#search_produk").val();
        let per_page = $("#per_page").val();

        $.ajax({
            url: "<?= site_url('produk/load_data'); ?>",
            type: "GET",
            dataType: "json",
            data: {
                page: page,
                kategori_id: kategori_id,
                status: status,
                search: search,
                per_page: per_page
            },
            success: function(response) {
                let produk_html = "";
                $.each(response.produk, function(index, produk) {
                    produk_html += `
                        <tr>
                            <td class="text-start">${produk.nama_produk}</td>
                            <td>${produk.sku}</td>
                            <td>${produk.nama_kategori}</td>
                            <td>${produk.satuan}</td>
                            <td class="text-end">Rp ${parseInt(produk.hpp).toLocaleString()}</td>
                            <td class="text-end">Rp ${parseInt(produk.harga_jual).toLocaleString()}</td>
                            <td>
                                ${produk.tampil == 1 
                                    ? '<span class="badge bg-success">Tampil</span>' 
                                    : '<span class="badge bg-secondary">Tidak Tampil</span>'
                                }
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit me-1" data-id="${produk.id}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= site_url('produk/hapus/'); ?>${produk.id}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus produk ini?')" title="Hapus"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>`;
                });

                $("#produk_list").html(produk_html);
                $("#pagination").html(response.pagination);
            }
        });
    }

    // Event Listeners
    loadProduk();
    $("#search_produk").on("keyup", () => loadProduk(1));
    $("#filter_kategori").on("change", () => loadProduk(1));
    $(".filter_status").on("click", function(e) {
        e.preventDefault();
        $(".filter_status").removeClass("active");
        $(this).addClass("active");
        loadProduk(1);
    });
    $("#per_page").on("change", () => loadProduk(1));
    $(document).on("click", "#pagination .page-link", function(e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadProduk(page);
    });

    // Tombol tambah
    // 🔹 Tombol Tambah
    $("#btnTambahProduk").on("click", function(e) {
        e.preventDefault();
        $("#formProduk")[0].reset();
        $("#modalProdukLabel").text("Tambah Produk");
        $("#produk-id").val('');
        $("#previewFoto").html('');
        $("#modalProduk").modal("show");
    });

    // 🔹 Tombol Edit Produk
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");

        $.get("<?= site_url('produk/get_produk_by_id/'); ?>" + id, function(res) {
            const data = JSON.parse(res);
            $("#modalProdukLabel").text("Edit Produk");
            $("#produk-id").val(data.id);
            $("#nama_produk").val(data.nama_produk);
            $("#sku").val(data.sku);
            $("#satuan").val(data.satuan);
            $("#harga_jual").val(data.harga_jual);
            $("#hpp").val(data.hpp);
            $("#kategori_id").val(data.kategori_id);
            $("#monitor_persediaan").val(data.monitor_persediaan);
            $("#tampil").val(data.tampil);
            $("#deskripsi").val(data.deskripsi);
            if (data.foto) {
                $("#previewFoto").html(
                    `<img src="<?= base_url('uploads/produk/'); ?>${data.foto}" width="100">`
                );
            } else {
                $("#previewFoto").html('');
            }
            $("#modalProduk").modal("show");
        });
    });

    $("#formProduk").on("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "<?= site_url('produk/simpan'); ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                const data = JSON.parse(res);
                if (data.status === 'success') {
                    $("#modalProduk").modal("hide");
                    loadProduk(); // reload data
                } else {
                    alert("Gagal menyimpan data.");
                }
            },
            error: function() {
                alert("Terjadi kesalahan saat menyimpan.");
            }
        });
    });


});
</script>