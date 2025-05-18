<div class="container-fluid mt-4">
    <!-- Menggunakan container-fluid agar tabel lebih lebar -->
    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Daftar Voucher</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalVoucher">Tambah Voucher</button>
        </div>

        <div class="row mt-3">
            <div class="col-lg-4 col-md-6">
                <input type="text" id="search-voucher" class="form-control" placeholder="Cari kode voucher...">
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
                        <th>Produk</th>
                        <th>Jumlah Gratis</th>
                        <th>Min. Pembelian</th>
                        <th>Maks. Diskon</th>
                        <th>Maks. Voucher</th> <!-- ✅ Tambahan -->
                        <th>Sisa Voucher</th> <!-- ✅ Tambahan -->
                        <th>Status</th>
                        <th>Masa Berlaku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="voucher-list">
                    <!-- Data voucher dimuat via AJAX -->
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted">Menampilkan <span id="data-info"></span> data</p>
            <nav>
                <ul class="pagination" id="pagination">
                    <!-- Pagination AJAX -->
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- MODAL FORM VOUCHER -->
<div class="modal fade" id="modalVoucher">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Voucher</h5>
            </div>
            <div class="modal-body">
                <form id="voucher-form">
                    <input type="hidden" id="voucher-id">

                    <div class="form-group">
                        <label>Kode Voucher</label>
                        <input type="text" id="kode-voucher" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Voucher</label>
                        <select id="jenis-voucher" class="form-control">
                            <option value="persentase">Diskon (%)</option>
                            <option value="nominal">Diskon (Rp)</option>
                            <option value="gratis_produk">Gratis Produk</option>
                            <option value="cashback">Cashback</option>
                            <option value="min_pembelian">Min. Pembelian</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nilai Voucher</label>
                        <input type="number" id="nilai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Pilih Produk (Opsional)</label>
                        <input type="text" id="search-produk" class="form-control" placeholder="Cari produk...">
                        <ul id="produk-list" class="list-group"></ul>
                        <input type="hidden" id="produk-id">
                    </div>

                    <div class="form-group">
                        <label>Jumlah Gratis (Opsional)</label>
                        <input type="number" id="jumlah-gratis" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Maksimal Diskon (Opsional, untuk Diskon %)</label>
                        <input type="number" id="max-diskon" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Minimal Pembelian (Rp)</label>
                        <input type="number" id="min-pembelian" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Maksimal Voucher</label>
                        <input type="number" id="maksimal-voucher" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Sisa Voucher</label>
                        <input type="number" id="sisa-voucher" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="status-voucher" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" id="tanggal-mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Berakhir</label>
                        <input type="date" id="tanggal-berakhir" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
    text-align: center;
    vertical-align: middle;
    font-size: 14px;
    white-space: nowrap;
    /* Mencegah wrap */
}

.table-hover tbody tr:hover {
    background-color: #f9f9f9;
}

.badge {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 5px;
}

.badge-aktif {
    background: #28a745;
    color: white;
}

.badge-nonaktif {
    background: #dc3545;
    color: white;
}

.pagination .page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.pagination .page-item .page-link {
    color: #333;
}
</style>


<script>
$(document).ready(function() {
    loadVoucher();

    function formatRupiah(angka) {
        return parseInt(angka).toLocaleString('id-ID');
    }

    function loadVoucher() {
        let search = $("#search-voucher").val();
        let status = $("#status-filter").val();
        let dateRange = $("#date-range").val();

        $.ajax({
            url: "<?= site_url('voucher/get_all'); ?>",
            type: "GET",
            dataType: "json",
            data: {
                search: search,
                status: status,
                date_range: dateRange
            },
            success: function(response) {
                let html = "";
                if (response.length === 0) {
                    html =
                        "<tr><td colspan='10' class='text-center'>Tidak ada voucher tersedia</td></tr>";
                } else {
                    $.each(response, function(index, voucher) {
                        let statusBadge = voucher.status === "aktif" ?
                            `<span class="badge badge-aktif">Aktif</span>` :
                            `<span class="badge badge-nonaktif">Nonaktif</span>`;

                        html += `
                            <tr>
                                <td>${voucher.kode_voucher}</td>
                                <td>${voucher.jenis}</td>
                                <td>${formatRupiah(voucher.nilai)}</td>
                                <td>${voucher.nama_produk || '-'}</td>
                                <td>${formatRupiah(voucher.jumlah_gratis) || '-'}</td>
                                <td>${formatRupiah(voucher.min_pembelian)}</td>
                                <td>${formatRupiah(voucher.max_diskon) || '-'}</td>
                                <td>${voucher.maksimal_voucher || '-'}</td>
                                <td>${voucher.sisa_voucher || '-'}</td>
                                <td>${statusBadge}</td>
                                <td>${voucher.tanggal_mulai} - ${voucher.tanggal_berakhir}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-voucher" data-id="${voucher.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-voucher" data-id="${voucher.id}">Hapus</button>
                                </td>
                            </tr>`;
                    });
                }
                $("#voucher-list").html(html);
            }
        });
    }

    // 🔍 Live Search AJAX
    $("#search-voucher").on("input", function() {
        loadVoucher();
    });


    // 🔍 Live Search AJAX
    $("#search-voucher").on("input", function() {
        loadVoucher();
    });

    // Fungsi Hapus Voucher
    $(document).on("click", ".delete-voucher", function() {
        let id = $(this).data("id");
        if (confirm("Hapus voucher ini?")) {
            $.post("<?= site_url('voucher/hapus'); ?>", {
                id: id
            }, function(response) {
                alert(response.message);
                loadVoucher();
            }, "json");
        }
    });
    // AJAX Pencarian Produk dengan Preview
    $("#search-produk").on("keyup", function() {
        let keyword = $(this).val().trim();
        if (keyword.length > 1) {
            $.ajax({
                url: "<?= site_url('voucher/search_produk'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    keyword: keyword
                },
                success: function(response) {
                    let produkHtml = "";
                    if (response.length > 0) {
                        $.each(response, function(index, produk) {
                            produkHtml +=
                                `<li class="list-group-item select-produk" data-id="${produk.id}" data-nama="${produk.nama_produk}">${produk.nama_produk}</li>`;
                        });
                    } else {
                        produkHtml =
                            `<li class="list-group-item text-muted">Produk tidak ditemukan</li>`;
                    }
                    $("#produk-list").html(produkHtml).show();
                }
            });
        } else {
            $("#produk-list").hide();
        }
    });

    // Pilih produk dari hasil pencarian
    $(document).on("click", ".select-produk", function() {
        $("#produk-id").val($(this).data("id"));
        $("#search-produk").val($(this).data("nama"));
        $("#produk-list").hide();
    });

    // Sembunyikan dropdown saat klik di luar
    $(document).click(function(e) {
        if (!$(e.target).closest("#search-produk, #produk-list").length) {
            $("#produk-list").hide();
        }
    });

    // Fungsi Tambah/Edit Voucher
    $("#voucher-form").on("submit", function(e) {
        e.preventDefault();

        let formData = {
            id: $("#voucher-id").val(),
            kode_voucher: $("#kode-voucher").val(),
            jenis: $("#jenis-voucher").val(),
            nilai: $("#nilai").val(),
            produk_id: $("#produk-id").val(),
            jumlah_gratis: $("#jumlah-gratis").val(),
            max_diskon: $("#max-diskon").val(),
            min_pembelian: $("#min-pembelian").val(),
            maksimal_voucher: $("#maksimal-voucher").val(),
            sisa_voucher: $("#sisa-voucher").val(),
            status: $("#status-voucher").val(),
            tanggal_mulai: $("#tanggal-mulai").val(),
            tanggal_berakhir: $("#tanggal-berakhir").val()
        };

        $.post("<?= site_url('voucher/simpan'); ?>", formData, function(response) {
            alert(response.message);

            if (response.status === "success") {
                $("#modalVoucher").modal("hide");
                loadVoucher();
            }
        }, "json");
    });

    $(document).on("click", ".edit-voucher", function() {
        let id = $(this).data("id");

        $.get("<?= site_url('voucher/get'); ?>", {
            id: id
        }, function(voucher) {
            $("#voucher-id").val(voucher.id);
            $("#kode-voucher").val(voucher.kode_voucher);
            $("#jenis-voucher").val(voucher.jenis);
            $("#nilai").val(voucher.nilai);
            $("#produk-id").val(voucher.produk_id);
            $("#jumlah-gratis").val(voucher.jumlah_gratis);
            $("#max-diskon").val(voucher.max_diskon);
            $("#min-pembelian").val(voucher.min_pembelian);
            $("#maksimal-voucher").val(voucher.maksimal_voucher);
            $("#sisa-voucher").val(voucher.sisa_voucher);
            $("#status-voucher").val(voucher.status);
            $("#tanggal-mulai").val(voucher.tanggal_mulai);
            $("#tanggal-berakhir").val(voucher.tanggal_berakhir);

            $(".modal-title").text("Edit Voucher");
            $("#modalVoucher").modal("show");
        }, "json");
    });

    // Fungsi Hapus Voucher
    $(document).on("click", ".delete-voucher", function() {
        let id = $(this).data("id");
        if (confirm("Hapus voucher ini?")) {
            $.post("<?= site_url('voucher/hapus'); ?>", {
                id: id
            }, function(response) {
                alert(response.message);
                loadVoucher();
            }, "json");
        }
    });

    // Reset modal saat ditutup untuk menghindari overlay hitam
    $("#modalVoucher").on("hidden.bs.modal", function() {
        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();
        $("#voucher-form")[0].reset();
        $("#voucher-id").val(""); // Reset ID agar tidak salah update
        $(".modal-title").text("Tambah Voucher");
    });



});
</script>