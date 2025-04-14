<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Daftar Produk</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="search_produk" class="form-control" placeholder="Cari Produk...">
        </div>
        <div class="col-md-4">
            <select id="filter_kategori" class="form-control">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select id="per_page" class="form-control">
                <option value="10">10 Baris</option>
                <option value="25">25 Baris</option>
                <option value="30" selected>30 Baris</option>
                <option value="50">50 Baris</option>
                <option value="all">Semua</option>
            </select>
        </div>
    </div>

    <ul class="nav nav-tabs">
        <li class="nav-item"><a href="#" class="nav-link filter_status active" data-status="">Semua</a></li>
        <li class="nav-item"><a href="#" class="nav-link filter_status" data-status="1">Tampil di Menu</a></li>
        <li class="nav-item"><a href="#" class="nav-link filter_status" data-status="2">Tidak Tampil di Menu</a></li>
    </ul>

    <table class="table table-striped mt-3">
        <thead class="text-center">
            <tr>
                <th>Nama Produk</th>
                <th>SKU</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>HPP</th>
                <th>Harga Jual</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="produk_list"></tbody>
    </table>

    <div class="text-center">
        <nav>
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>

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
                            <td>${produk.nama_produk}</td>
                            <td>${produk.sku}</td>
                            <td>${produk.nama_kategori}</td>
                            <td>${produk.satuan}</td>
                            <td class="text-right">Rp ${produk.hpp.toLocaleString()}</td>
                            <td class="text-right">Rp ${produk.harga_jual.toLocaleString()}</td>
                            <td class="text-center">
                                ${produk.tampil == 1 ? '<span class="badge bg-success">Tampil</span>' : '<span class="badge bg-secondary">Tidak Tampil</span>'}
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('produk/edit/'); ?>${produk.id}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('produk/hapus/'); ?>${produk.id}" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    `;
                });

                $("#produk_list").html(produk_html);
                $("#pagination").html(response.pagination);
            }
        });
    }

    loadProduk();

    $("#search_produk").on("keyup", function() { loadProduk(1); });
    $("#filter_kategori").on("change", function() { loadProduk(1); });
    $(".filter_status").on("click", function(e) {
        e.preventDefault();
        $(".filter_status").removeClass("active");
        $(this).addClass("active");
        loadProduk(1);
    });
    $("#per_page").on("change", function() { loadProduk(1); });
    $(document).on("click", ".pagination a", function(e) {
        e.preventDefault();
        let page = $(this).data("ci-pagination-page");
        loadProduk(page);
    });
});



    </script>
</body>
</html>
