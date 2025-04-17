<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Namua Coffee & Eatery</title>
    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- <title><?= isset($title) ? $title : 'Admin Panel' ?></title> Title dinamis -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




    <style>
    /* Fullscreen Mode */
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    }

    .container-fluid {
        display: flex;
        height: 100vh;
    }

    /* Sidebar pesanan belum dibayar */
    .sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        background: #f8f9fa;
        padding: 10px;
        border-right: 1px solid #ddd;
    }

    /* Container untuk pending orders dengan scrolling */
    #pending-orders-container {
        flex-grow: 1;
        overflow-y: auto;
        padding-bottom: 10px;
    }

    /* Pending Orders */
    #pending-orders {
        max-height: 70vh;
        /* Batasi tinggi agar tidak menutupi tombol */
    }

    /* Menu tindakan tetap di bawah */
    #menu-actions {
        position: relative;
        bottom: 0;
        width: 100%;
        background: #ffffff;
        padding: 10px;
        border-top: 1px solid #ddd;
    }

    .pesanan-item {
        background: #fff;
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .pesanan-item:hover {
        background: #e9ecef;
    }

    .pesanan-item.selected {
        background: #343a40 !important;
        /* Warna abu-abu gelap */
        color: #fff !important;
        /* Warna teks putih */
        font-weight: bold;
    }


    /* Area kasir utama */
    .kasir-content {
        width: 80%;
        padding: 10px;
        overflow-y: auto;
    }

    /* Kategori Produk */
    #kategori-tab button {
        font-size: 12px;
        padding: 5px 10px;
        margin: 2px;
    }

    /* Produk */
    #produk-list h5 {
        font-size: 14px;
        font-weight: bold;
    }

    #produk-list p {
        font-size: 12px;
        color: #555;
    }

    #produk-list .card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }

    #produk-list .card {
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    #produk-list .card:hover {
        transform: scale(1.05);
    }

    .add-to-cart {
        font-size: 12px;
        padding: 5px;
    }

    .extra-row {
        background-color: #f9f9f9;
    }

    .list-extra {
        list-style-type: none;
        padding-left: 15px;
        margin: 0;
    }

    .list-extra li {
        border-bottom: 1px dashed #ccc;
        padding: 2px 0;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar Pesanan Belum Dibayar -->
        <div class="sidebar">
            <h4>Pesanan Belum Dibayar</h4>

            <!-- Tombol Cetak -->
            <!-- <button id="btn-cetak" class="btn btn-dark mb-2">
                <i class="fas fa-print"></i> Cetak
            </button> -->
            <button id="btn-cetak-divisi" class="btn btn-info mt-2">Cetak per Divisi</button>
            <button id="btn-cetak-baru" class="btn btn-dark mt-2">Cetak Pesanan Baru</button>


            <!-- Pending Orders dengan scrolling -->
            <div id="pending-orders-container">
                <!-- <div id="pending-orders"></div> -->
                <div id="pending-orders" class="mt-2"></div>
            </div>

            <!-- Menu Aksi -->
            <div id="menu-actions">
                <button id="ubah-pesanan" class="btn btn-primary">Ubah Pesanan</button>
                <button id="rincian-pesanan" class="btn btn-info">Lihat Rincian</button>
                <button id="cetak-kot" class="btn btn-warning">Cetak ulang KOT</button>
                <button id="faktur" class="btn btn-info">Faktur</button>
                <button id="tagihan" class="btn btn-success">Tagihan</button>
                <button id="batalkan-pesanan" class="btn btn-danger">Batalkan Pesanan</button>
            </div>
        </div>


        <!-- Area Utama POS -->
        <div class="kasir-content">
            <h2 class="text-center mt-3">POS - Namua Coffee & Eatery</h2>

            <div class="row">
                <div class="col-md-6">
                    <h4>Detail Pesanan</h4>

                    <!-- Jenis Order -->
                    <div class="form-group">
                        <label>Jenis Order</label>
                        <select id="jenis-order" class="form-control">
                            <?php foreach ($jenis_order as $jo): ?>
                            <option value="<?= $jo['id']; ?>" <?= $jo['id'] == 1 ? 'selected' : '' ?>>
                                <?= $jo['jenis_order']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <!-- Customer -->
                    <div class="form-group">
                        <label>Pilih Customer</label>
                        <select id="customer-type" class="form-control">
                            <option value="member">Customer Member</option>
                            <option value="walkin">Walk-in Customer</option>
                        </select>
                    </div>

                    <div class="form-group" id="customer-member">
                        <label>Cari Customer</label>
                        <input type="text" id="search-customer" class="form-control" placeholder="Cari Nama Customer">
                        <ul id="customer-list" class="list-group"></ul>
                    </div>

                    <div class="form-group" id="customer-walkin" style="display:none;">
                        <label>Nama Customer</label>
                        <input type="hidden" id="customer-id" value="">
                        <input type="text" id="walkin-customer-name" class="form-control"
                            placeholder="Masukkan Nama Customer">
                    </div>

                    <!-- Nomor Meja -->
                    <div class="form-group">
                        <label>Nomor Meja</label>
                        <input type="text" id="nomor-meja" class="form-control"
                            placeholder="Masukkan nomor meja (jika ada)">
                    </div>
                    <!-- <div class="form-group">
                        <label>Kode Voucher</label>
                        <div class="input-group">
                            <input type="text" id="kode-voucher" class="form-control"
                                placeholder="Masukkan kode voucher">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="cek-voucher">Gunakan</button>
                            </div>
                        </div>
                        <p id="voucher-message" class="text-success"></p>
                    </div> -->

                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Extra</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="order-list"></tbody>
                    </table>

                    <!-- Total Harga Sebelum Diskon -->
                    <h4>Total Harga: <span id="total-harga">Rp 0</span></h4>

                    <!-- Nominal Diskon -->
                    <!-- <h4>Diskon: <span id="nominal-diskon">Rp 0</span></h4> -->

                    <!-- Total Bayar Setelah Diskon -->
                    <h3>Total Bayar: <span id="total-bayar">Rp 0</span></h3>
                    <!-- <h4>Total Bayar: <span id="total-bayar">Rp 0</span></h4> -->
                    <button id="btn-batal" class="btn btn-danger">Batal</button>
                    <input type="hidden" id="transaksi-id" value="">
                    <button class="btn btn-warning" id="simpan-transaksi">Simpan Pesanan</button>
                    <button class="btn btn-info" id="simpan-perubahan" style="display:none;">Simpan Perubahan</button>
                    <button id="btn-selesaikan-pembayaran" class="btn btn-success">Selesaikan Pembayaran</button>

                </div>

                <div class="col-md-6">
                    <h4>Cari Produk</h4>
                    <input type="text" id="search" class="form-control" placeholder="Cari produk...">
                    <h4 class="mt-3">Kategori</h4>
                    <div id="kategori-tab">
                        <button class="btn btn-outline-dark active" data-kategori="">Semua</button>
                        <?php foreach ($kategori as $k): ?>
                        <button class="btn btn-outline-dark" data-kategori="<?= $k['id']; ?>">
                            <?= $k['nama_kategori']; ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <h4 class="mt-3">Daftar Produk</h4>
                    <div class="row" id="produk-list"></div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Konfirmasi Batal -->
    <div id="modalBatal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peringatan!</h5>
                </div>
                <div class="modal-body">
                    <p>Keranjang belanja tidak kosong, ingin mengosongkan keranjang belanja?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="confirmBatal" class="btn btn-primary">OKE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Void Produk Lama -->
    <div class="modal fade" id="modalVoidConfirm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white py-2">
                    <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                </div>
                <div class="modal-body text-center">
                    <p>Produk ini sudah dicetak dan akan di-VOID.</p>
                    <p class="text-danger mb-0"><small>Pastikan Anda konfirmasi ke dapur/bar terlebih dahulu.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger btn-sm" id="confirm-void-btn">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Cetak Per Divisi (Berdasarkan Printer) -->
    <div class="modal fade" id="modalCetakDivisi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="form-cetak-divisi" method="post" action="<?= site_url('kasir/preview_struk_printer') ?>">
                <input type="hidden" name="transaksi_id" id="transaksi_id_cetak_divisi">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Printer untuk Cetak</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="lokasi_printer">Pilih Printer</label>
                            <select name="lokasi_printer" id="lokasi_printer" class="form-control" required>
                                <option value="">-- Pilih Printer --</option>
                                <?php foreach ($printer as $p): ?>
                                <option value="<?= $p['lokasi_printer'] ?>">
                                    <?= strtoupper($p['lokasi_printer']) ?>
                                    <?= $p['divisi'] ? '(' . strtoupper($p['divisi_nama']) . ')' : '(SEMUA DIVISI)' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Preview & Cetak</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Rincian Pesanan -->
    <div class="modal fade" id="modalRincianPesanan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-sm border-0">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Rincian Pesanan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nomor Transaksi:</strong> <span id="rinci-no-transaksi"></span></p>
                            <p><strong>Customer:</strong> <span id="rinci-customer"></span></p>
                            <p><strong>Voucher:</strong> <span id="rinci-voucher">-</span></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Jenis Order:</strong> <span id="rinci-jenis-order">-</span></p>
                            <p><strong>Nomor Meja:</strong> <span id="rinci-meja">-</span></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-voucher">Masukkan Kode Voucher:</label>
                        <div class="input-group">
                            <input type="text" id="input-voucher" class="form-control" placeholder="Kode Voucher">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btn-check-voucher">Cek Voucher</button>
                                <button class="btn btn-danger" id="reset-voucher">Reset
                                    Voucher</button>

                            </div>
                        </div>
                        <small id="voucher-message" class="text-success"></small>
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="rinci-item-list"></tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Diskon:</strong> <span id="rinci-diskon">Rp 0</span></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4>Total Bayar: Rp <span id="rinci-total">0</span></h4>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-success" id="btn-buka-bayar">Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Multi Pembayaran -->
    <div class="modal fade" id="modalPembayaran" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form id="formPembayaranMulti">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Menyelesaikan Penjualan</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Bagian Metode -->
                            <div class="col-md-4">
                                <h5>Metode Pembayaran</h5>
                                <div id="metode-pembayaran-list" class="list-group">
                                    <!-- Akan diisi default TUNAI via JS -->
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="btn-tambah-metode">
                                    + Tambah Metode
                                </button>
                            </div>

                            <!-- Bagian Input -->
                            <div class="col-md-8">
                                <h5>Input Pembayaran</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembayaran-multi"></tbody>
                                </table>
                                <div class="row mb-2">
                                    <div class="col">
                                        <p>Dapat Dibayar: <strong id="multi-tagihan">Rp 0</strong></p>
                                        <p>Total Dibayar: <strong id="multi-total-dibayar">Rp 0</strong></p>
                                        <p>Sisa Pembayaran: <strong id="multi-sisa">Rp 0</strong></p>
                                    </div>
                                    <div class="col text-right">
                                        <label>Input Cepat:</label><br>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="10000">10K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="20000">20K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="50000">50K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="100000">100K</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="transaksi_id" id="bayar-transaksi-id">
                                <button type="submit" class="btn btn-success">Selesaikan Pembayaran</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalPilihMetode" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="list-metode-pembayaran">
                    <!-- Diisi lewat JS -->
                </div>
            </div>
        </div>
    </div>


    <!-- FUNGSI CETAK -->
    <button id="print-bluetooth" class="btn btn-primary">
        <i class="fas fa-print"></i> Cetak Bluetooth
    </button>

    <div class="modal fade" id="modalExtra" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Extra</h5>
                </div>
                <div class="modal-body">
                    <div id="list-extra-modal"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="simpan-extra">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const base_url = "<?= base_url(); ?>";

    $(document).ready(function() {
        let extraData = {};
        let currentProdukId = null;
        let currentUID = null;
        let selectedOrderId = null;
        let deletedItems = [];
        let rinciOrderItems = [];
        let pembayaranList = [];
        let tagihanTotal = 0;

        function formatRupiah(angka) {
            return "Rp " + parseInt(angka).toLocaleString("id-ID");
        }

        function updateTotal() {
            let total = 0;
            $("#order-list tr[data-id]").each(function() {
                const qty = parseInt($(this).find(".qty").val()) || 1;
                const harga = parseInt($(this).find(".qty").data("harga")) || 0;
                const uid = $(this).data("uid");

                let subtotal = qty * harga;
                if (extraData[uid]) {
                    extraData[uid].forEach(extra => {
                        subtotal += extra.harga * extra.jumlah;
                    });
                }

                $(this).find(".total").text(formatRupiah(subtotal));
                total += subtotal;
            });

            // const diskon = parseInt($("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, "")) || 0;
            // const totalBayar = total - diskon;
            const totalBayar = total;

            $("#total-harga").text(formatRupiah(total));
            $("#total-bayar").text(formatRupiah(totalBayar > 0 ? totalBayar : 0));
        }

        $("#search-customer").on("keyup", function() {
            let search = $(this).val().trim();
            if (search.length > 1) {
                $.ajax({
                    url: base_url + "kasir/search_customer",
                    type: "GET",
                    dataType: "json",
                    data: {
                        search
                    },
                    success: function(response) {
                        let customerHtml = "";
                        $.each(response, function(index, customer) {
                            customerHtml += `<li class="list-group-item select-customer" data-id="${customer.id}" data-nama="${customer.nama}">
                            ${customer.nama} - ${customer.telepon}</li>`;
                        });
                        $("#customer-list").html(customerHtml).show();
                    },
                });
            } else {
                $("#customer-list").hide();
            }
        });

        $(document).on("click", ".select-customer", function() {
            let nama = $(this).data("nama");
            let id = $(this).data("id");

            $("#search-customer").val(nama);
            $("#customer-id").val(id);
            $("#customer-list").hide();
        });

        function loadProduk(kategori = "", search = "") {
            $.ajax({
                url: base_url + "kasir/load_produk",
                type: "GET",
                dataType: "json",
                data: {
                    kategori,
                    search
                },
                success: function(response) {
                    let produkHtml = "";
                    $.each(response, function(index, produk) {
                        produkHtml += `
                    <div class="col-md-4">
                        <div class="card text-center p-2">
                            <img src="${base_url}uploads/produk/${produk.foto}" class="img-fluid">
                            <h5>${produk.nama_produk}</h5>
                            <p>${formatRupiah(produk.harga_jual)}</p>
                            <button class="btn btn-primary add-to-cart"
                                data-id="${produk.id}"
                                data-nama="${produk.nama_produk}"
                                data-harga="${produk.harga_jual}">
                                Tambah
                            </button>
                        </div>
                    </div>`;
                    });
                    $("#produk-list").html(produkHtml);
                }
            });
        }

        $("#search").on("keyup", function() {
            let search = $(this).val().trim();
            let kategori = $("#kategori-tab .active").data("kategori");
            loadProduk(kategori, search);
        });

        $("#kategori-tab button").on("click", function() {
            $("#kategori-tab button").removeClass("active");
            $(this).addClass("active");

            let kategori = $(this).data("kategori");
            let search = $("#search").val().trim();
            loadProduk(kategori, search);
        });

        loadProduk();

        $(document).on("click", ".add-to-cart", function() {
            const id = $(this).data("id");
            const nama = $(this).data("nama");
            const harga = parseInt($(this).data("harga"));

            const uid = Date.now(); // unique row id for extra reference

            const row = `
        <tr data-id="${id}" data-uid="${uid}">
            <td>${nama}</td>
            <td>${formatRupiah(harga)}</td>
            <td><input type="number" class="form-control qty" value="1" min="1" data-harga="${harga}"></td>
            <td class="total">${formatRupiah(harga)}</td>
            <td>
                <button class="btn btn-sm btn-secondary btn-extra" data-id="${id}" data-uid="${uid}">Tambah Extra</button>
            </td>
            <td><input type="text" class="form-control catatan" placeholder="Tambahkan catatan (opsional)"></td>
            <td><button class="btn btn-danger btn-sm delete-item"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
        <tr class="extra-row" data-parent="${uid}">
            <td colspan="7">
                <ul class="list-extra pl-4 mb-0 text-muted small" data-uid="${uid}"></ul>
            </td>
        </tr>`;

            $("#order-list").append(row);
            updateTotal();
        });
        $(document).on("input", ".qty", function() {
            const qty = parseInt($(this).val()) || 1;
            const harga = parseInt($(this).data("harga")) || 0;
            const uid = $(this).closest("tr").data("uid");

            let total = qty * harga;

            if (extraData[uid]) {
                extraData[uid].forEach(extra => {
                    total += extra.harga * extra.jumlah;
                });
            }

            $(this).closest("tr").find(".total").text(formatRupiah(total));
            updateTotal();
        });


        $(document).on("click", ".btn-extra", function() {
            currentProdukId = $(this).data("id");
            currentUID = $(this).data("uid");

            $.get(base_url + "kasir/get_extra_list", function(data) {
                const list = JSON.parse(data);
                let html = "";

                list.forEach(extra => {
                    html += `
        <div class="form-check">
            <input type="checkbox" class="form-check-input extra-check"
                data-id="${extra.id}"
                data-sku="${extra.sku}"
                data-nama="${extra.nama_extra}"
                data-satuan="${extra.satuan}"
                data-harga="${extra.harga}"
                data-hpp="${extra.hpp}"
                id="extra-${extra.id}">
            <label class="form-check-label" for="extra-${extra.id}">
                ${extra.nama_extra} - Rp ${parseInt(extra.harga).toLocaleString()}
            </label>
        </div>`;
                });

                $("#list-extra-modal").html(html);
                $("#modalExtra").modal("show");
            });

        });

        $(document).on("change", ".extra-check", function() {
            const id = $(this).data("id");
            $(`.jumlah-extra[data-id=${id}]`).prop("disabled", !$(this).is(":checked"));
        });

        $("#simpan-extra").click(function() {
            let selected = [];
            const jumlahProduk = parseInt($(`[data-uid="${currentUID}"]`).find(".qty").val()) || 1;

            $(".extra-check:checked").each(function() {
                const id = $(this).data("id");
                const nama = $(this).data("nama");
                const sku = $(this).data("sku");
                const satuan = $(this).data("satuan");
                const harga = parseInt($(this).data("harga"));
                const hpp = parseInt($(this).data("hpp"));

                selected.push({
                    id,
                    nama,
                    sku,
                    satuan,
                    harga,
                    hpp,
                    jumlah: jumlahProduk // ✅ sesuaikan dengan jumlah produk
                });
            });

            extraData[currentUID] = selected;

            const list = selected.map(e =>
                `<li>
                    ➕ ${e.jumlah}x ${e.nama} 
                    <span class="float-right">Rp ${e.harga.toLocaleString('id-ID')}</span>
                    <button class='btn btn-sm btn-outline-danger btn-hapus-extra ml-1'><i class='fas fa-times'></i></button>
                </li>`
            ).join("");

            $(`.list-extra[data-uid="${currentUID}"]`).html(list);
            updateTotal();
            $("#modalExtra").modal("hide");
        });



        // Fungsi bantu untuk mengambil data transaksi
        function getOrderData() {
            return {
                transaksi_id: $("#transaksi-id").val(),
                jenis_order_id: $("#jenis-order").val(),
                customer_type: $("#customer-type").val(),
                customer_id: $("#customer-id").val(),
                customer: $("#search-customer").val() || $("#walkin-customer-name").val(),
                nomor_meja: $("#nomor-meja").val(),
                // kode_voucher: $("#kode-voucher").val(),
                // diskon: $("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, ""),
                items: []
            };
        }

        // Simpan transaksi baru atau ubah
        $("#simpan-transaksi, #simpan-perubahan").on("click", function() {
            let orderData = getOrderData();

            orderData.transaksi_id = $("#transaksi-id").val(); // ✅ penting!

            $("#order-list tr[data-id]").each(function() {
                const uid = $(this).data("uid");

                orderData.items.push({
                    pr_produk_id: $(this).data("id"),
                    detail_id: $(this).data("detail-id") ?? null,
                    jumlah: $(this).find(".qty").val(),
                    harga: $(this).find(".qty").data("harga"),
                    subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
                        /\./g, ""),
                    catatan: $(this).find(".catatan").val(),
                    extra: extraData[$(this).data("uid")] || [],
                    is_printed: $(this).data("printed") || 0
                });
            });

            $.ajax({
                url: base_url + "kasir/simpan_transaksi",
                type: "POST",
                dataType: "json",
                data: {
                    order_data: JSON.stringify(orderData)
                },
                success: function(response) {
                    alert((response.status === "success" ? "✅" : "❌") + " " + response
                        .message);
                    if (response.status === "success") {
                        kosongkanKeranjang();
                        resetFormToBaru();
                        loadPendingOrders();
                    }
                }
            });
        });

        function resetFormToBaru() {
            $("#transaksi-id").val("");
            $("#simpan-perubahan").hide();
            $("#simpan-transaksi").show();
        }

        //cetak pesanan baru

        $("#btn-cetak-baru").click(function() {
            let selected = $(".pesanan-item.selected");
            if (selected.length === 0) {
                alert("Pilih salah satu pesanan terlebih dahulu!");
                return;
            }

            let transaksi_id = selected.data("id");

            $.post("<?= site_url('kasir/cetak_pesanan_baru') ?>", {
                transaksi_id: transaksi_id
            }, function(res) {
                alert(res.message);
                if (res.status === 'success') {
                    loadPendingOrders();
                }
            }, "json");

        });
        // Cetak per divisi
        $("#btn-cetak-divisi").click(function() {
            let selected = $(".pesanan-item.selected");
            if (selected.length === 0) {
                alert("Pilih salah satu pesanan terlebih dahulu!");
                return;
            }
            let transaksi_id = selected.data("id");
            $("#transaksi_id_cetak_divisi").val(transaksi_id);
            $("#modalCetakDivisi").modal("show");
        });

        // Cetak struk
        function openModalCetak(transaksiId) {
            selectedTransaksiId = transaksiId;
            $('#modalCetak').modal("show");
            loadPrinterList();
        }

        function loadPrinterList() {
            $.get(base_url + 'kasir/get_printer_list', function(data) {
                let printers = JSON.parse(data);
                $('#printer_select').empty().append('<option value="">Pilih Printer</option>');
                printers.forEach(function(p) {
                    $('#printer_select').append(
                        `<option value="${p.lokasi_printer}">${p.divisi.toUpperCase()} - ${p.lokasi_printer}</option>`
                    );
                });
            });
        }

        function printStruk(divisi) {
            const printer = $('#printer_select').val();
            if (!printer) {
                alert("Pilih printer terlebih dahulu!");
                return;
            }

            $.ajax({
                url: base_url + "kasir/cetak_struk/" + selectedTransaksiId + "/" + divisi,
                method: "GET",
                data: {
                    printer: printer
                },
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        alert("Berhasil dikirim ke printer: " + res.printer);
                    } else {
                        alert("Gagal cetak: " + res.message);
                    }
                }
            });
        }

        // Tombol batal
        $("#btn-batal").click(function() {
            $("#modalBatal").modal("show");
        });

        $("#confirmBatal").click(function() {
            $("#modalBatal").modal("hide");
            kosongkanKeranjang();
        });

        function kosongkanKeranjang() {
            $("#order-list").empty();
            $("#total-harga").text("Rp 0");
            $("#nominal-diskon").text("Rp 0");
            $("#total-bayar").text("Rp 0");
            $("#nomor-meja").val("");
            $("#search-customer").val("");
            $("#walkin-customer-name").val("");
            // $("#kode-voucher").val("");
            // $("#voucher-message").text("");
            $("#customer-id").val("");
            extraData = {};
        }

        function loadPendingOrders() {
            $.get(base_url + "kasir/load_pending_orders", function(data) {
                let orders = JSON.parse(data);
                let html = "";
                if (orders.length === 0) {
                    html = "<p class='text-muted text-center mt-2'>Tidak ada pesanan</p>";
                } else {
                    orders.forEach(order => {
                        html +=
                            `<div class="pesanan-item" data-id="${order.id}"><div><strong>${order.no_transaksi}</strong></div><div>${order.customer}</div><div>Rp ${parseInt(order.total_pembayaran).toLocaleString("id-ID")}</div></div>`;
                    });
                }
                $("#pending-orders").html(html);
            });
        }
        $(document).on("click", ".pesanan-item", function() {
            $(".pesanan-item").removeClass("selected");
            $(this).addClass("selected");
            selectedOrderId = $(this).data("id");
        });

        loadPendingOrders();

        // Fungsi ubah pesanan
        $("#ubah-pesanan").click(function() {
            if (!selectedOrderId) {
                alert("Pilih salah satu pesanan terlebih dahulu!");
                return;
            }

            $.ajax({
                url: base_url + "kasir/get_detail_transaksi",
                type: "POST",
                dataType: "json",
                data: {
                    transaksi_id: selectedOrderId
                },
                success: function(res) {
                    kosongkanKeranjang();

                    // $("#transaksi-id").val(res.transaksi_id);
                    $("#transaksi-id").val(res.id); // ✅ karena field id = transaksi_id

                    $("#jenis-order").val(res.jenis_order_id);
                    $("#nomor-meja").val(res.nomor_meja);

                    if (res.customer_id) {
                        $("#customer-type").val("member").change();
                        $("#search-customer").val(res.customer);
                        $("#customer-id").val(res.customer_id);
                    } else {
                        $("#customer-type").val("walkin").change();
                        $("#walkin-customer-name").val(res.customer);
                    }

                    // Tampilkan tombol simpan perubahan
                    $("#simpan-transaksi").hide();
                    $("#simpan-perubahan").show();

                    // 1. Group by detail_unit_id
                    const grouped = {};
                    res.items.forEach((item) => {
                        if (!grouped[item.detail_unit_id]) {
                            grouped[item.detail_unit_id] = {
                                produk_id: item.pr_produk_id,
                                nama_produk: item.nama_produk,
                                harga: item.harga,
                                catatan: item.catatan,
                                is_printed: item.is_printed,
                                extras: [],
                                jumlah: 0,
                                detail_ids: []
                            };
                        }

                        grouped[item.detail_unit_id].jumlah += 1;
                        grouped[item.detail_unit_id].detail_ids.push(item.id);

                        if (item.extra && item.extra.length > 0) {
                            grouped[item.detail_unit_id].extras = item.extra;
                        }
                    });

                    // 2. Render satu baris per grup
                    Object.entries(grouped).forEach(([unitId, item], i) => {
                        const uid = Date.now() + i;
                        extraData[uid] = item.extras;

                        let row = `
                            <tr data-id="${item.produk_id}" data-uid="${uid}" data-detail-id="${unitId}" 
                                data-detail-ids='${JSON.stringify(item.detail_ids)}'
                                data-printed="${item.is_printed}">
                                <td>${item.nama_produk}</td>
                                <td>${formatRupiah(item.harga)}</td>
                                <td>
                                    <input type="number" class="form-control qty" value="${item.jumlah}" min="1"
                                        data-harga="${item.harga}"
                                        ${item.is_printed == 1 ? 'readonly disabled' : ''}>
                                </td>
                                <td class="total">${formatRupiah(item.jumlah * item.harga)}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary btn-extra" 
                                        data-id="${item.produk_id}" data-uid="${uid}"
                                        ${item.is_printed == 1 ? 'disabled' : ''}>
                                        Tambah Extra
                                    </button>
                                </td>
                                <td><input type="text" class="form-control catatan" value="${item.catatan ?? ''}"></td>
                                <td>
                                    ${item.is_printed == 1 ? '' : '<button class="btn btn-danger btn-sm delete-item"><i class="fas fa-trash-alt"></i></button>'}
                                </td>
                            </tr>
                            <tr class="extra-row" data-parent="${uid}">
                                <td colspan="7">
                                    <ul class="list-extra pl-4 mb-0 text-muted small" data-uid="${uid}">
                                        ${
                                            (item.extras || []).map(e => 
                                                `<li>
                                                    ➕ ${e.jumlah}x ${e.nama}
                                                    <span class="float-right">Rp ${(e.harga * e.jumlah).toLocaleString('id-ID')}</span>
                                                </li>`
                                            ).join("")
                                        }
                                    </ul>
                                </td>
                            </tr>
                            `;

                        $("#order-list").append(row);
                    });


                    updateTotal();
                }

            });
        });

        let voidTargetRow = null;

        $(document).on("click", ".delete-item", function() {
            const $tr = $(this).closest("tr");
            const isPrinted = $tr.data("printed") === 1;

            if (isPrinted) {
                voidTargetRow = $tr;
                $("#modalVoidConfirm").modal("show");
            } else {
                removeRow($tr); // ❌ TIDAK ADA DEFINISI FUNGSI removeRow
            }
        });

        $("#confirm-void-btn").on("click", function() {
            if (voidTargetRow) {
                // Simpan void ke pr_void (opsional via backend nanti)
                const detailId = voidTargetRow.data("detail-id");
                if (detailId) {
                    // Kirim ke server jika perlu
                    $.post(base_url + "kasir/void_item", {
                        detail_id: detailId
                    });
                }

                removeRow(voidTargetRow);
                voidTargetRow = null;
                $("#modalVoidConfirm").modal("hide");
            }
        });

        $(document).on("click", ".btn-kurangi-extra", function() {
            const uid = $(this).closest("ul").data("uid");
            const index = $(this).closest("li").index();

            if (extraData[uid][index].jumlah > 1) {
                extraData[uid][index].jumlah--;
            } else {
                return; // jangan kurangi di bawah 1
            }

            refreshExtraList(uid);
            updateTotal();
        });

        $(document).on("click", ".btn-hapus-extra", function() {
            const uid = $(this).closest("ul").data("uid");
            const index = $(this).closest("li").index();

            const isPrinted = $(`[data-uid='${uid}']`).data("printed") === 1;
            if (isPrinted) {
                alert(
                    "❌ Extra lama tidak dapat dihapus langsung. Silakan konfirmasi void."
                );
                return;
            }

            extraData[uid].splice(index, 1);
            refreshExtraList(uid);
            updateTotal();
        });

        function removeExtra(uid, extraId) {
            if (extraData[uid]) {
                extraData[uid] = extraData[uid].filter(e => e.id !== extraId);
                const $row = $(`[data-uid="${uid}"]`);
                const qty = parseInt($row.find(".qty").val()) || 1;
                const harga = parseInt($row.find(".qty").data("harga")) || 0;
                let total = qty * harga;
                const list = extraData[uid].map(e =>
                    `<li>➕ ${e.jumlah}x ${e.nama} <span class="float-right text-danger delete-extra" data-uid="${uid}" data-id="${e.id}"><i class="fas fa-times-circle"></i></span></li>`
                ).join("");

                extraData[uid].forEach(e => {
                    total += e.harga * e.jumlah;
                });

                $(`[data-uid="${uid}"]`).find(".total").text(formatRupiah(total));
                $(`.list-extra[data-uid="${uid}"]`).html(list);
                updateTotal();
            }
        }

        $("#confirm-void-btn").on("click", function() {
            if (voidTargetRow && typeof voidTargetRow === 'object' && voidTargetRow.uid &&
                voidTargetRow.extraId) {
                removeExtra(voidTargetRow.uid, voidTargetRow.extraId);
                voidTargetRow = null;
                $("#modalVoidConfirm").modal("hide");
            }
        });

        function refreshExtraList(uid) {
            const list = extraData[uid].map(e =>
                `<li>
            ➕ ${e.jumlah}x ${e.nama}
            <span class="float-right">Rp ${(e.harga * e.jumlah).toLocaleString('id-ID')}</span>
            <button class='btn btn-sm btn-outline-warning btn-kurangi-extra ml-2'>-</button>
            <button class='btn btn-sm btn-outline-danger btn-hapus-extra ml-1'><i class='fas fa-times'></i></button>
        </li>`
            ).join("");

            $(`.list-extra[data-uid="${uid}"]`).html(list);
        }

        function removeRow($tr) {
            const uid = $tr.data("uid");
            $(`[data-parent="${uid}"]`).remove(); // Hapus baris extra
            $tr.remove(); // Hapus baris utama
            delete extraData[uid]; // Bersihkan data extra dari memori
            updateTotal(); // Perbarui total
        }



        /// PEMBAYARAN
        $("#rincian-pesanan").click(function() {
            const selected = $(".pesanan-item.selected");
            if (!selected.length) {
                alert("Pilih salah satu pesanan!");
                return;
            }

            const transaksi_id = selected.data("id");

            $.post(base_url + "kasir/get_detail_transaksi", {
                transaksi_id
            }, function(res) {
                console.log(res); // ⬅️ Tambahkan ini                
                // // Info transaksi
                $("#rinci-no-transaksi").text(res.no_transaksi);
                $("#rinci-customer").text(res.customer || "-");
                $("#rinci-jenis-order").text(res.jenis_order || "-");
                $("#rinci-meja").text(res.nomor_meja || "-");
                $("#rinci-voucher").text(res.kode_voucher || "-");
                $("#rinci-diskon").text("Rp " + (res.diskon || 0).toLocaleString("id-ID"));
                $("#rinci-total").text((res.total_pembayaran || res.total_penjualan)
                    .toLocaleString("id-ID"));


                // ⬅️ Tambahkan ini di SINI
                rinciOrderItems = res.items;

                // Daftar item + ekstra

                const itemHtml = res.items.map(item => {
                    let html = `
                    <tr>
                    <td>${item.nama_produk}</td>
                    <td class="text-center">Rp ${item.harga.toLocaleString("id-ID")}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-right">Rp ${(item.harga * item.jumlah).toLocaleString("id-ID")}</td>
                    </tr>`;

                    if (item.extra?.length) {
                        item.extra.forEach(extra => {
                            html += `
                    <tr class="text-muted small">
                        <td class="pl-4">➕ ${extra.nama}</td>
                        <td class="text-center">Rp ${extra.harga.toLocaleString("id-ID")}</td>
                        <td class="text-center">${extra.jumlah}</td>
                        <td class="text-right">Rp ${(extra.harga * extra.jumlah).toLocaleString("id-ID")}</td>
                    </tr>`;
                        });
                    }

                    return html;
                }).join("");

                $("#rinci-item-list").html(itemHtml);
                $("#modalRincianPesanan").modal("show");

                // simpan transaksi_id ke tombol Bayar
                $("#btn-buka-bayar").data("id", transaksi_id);
            }, "json");
        });

        // Tambah ke dalam fungsi sukses get_detail_transaksi
        //        rinciOrderItems = res.items;

        // Fungsi cek voucher
        $("#btn-check-voucher").click(function() {
            const kode = $("#input-voucher").val().trim();
            const total = parseInt($("#rinci-total").text().replace(/\D/g, "")) || 0;

            if (!kode || rinciOrderItems.length === 0) {
                alert("Mohon lengkapi data transaksi.");
                return;
            }

            // Hitung subtotal jika belum ada
            // rinciOrderItems.forEach(item => {
            //     item.subtotal = (item.harga * item.jumlah) +
            //         (item.extra?.reduce((t, e) => t + (e.harga * e.jumlah), 0) || 0);
            // });
            const itemsForVoucher = rinciOrderItems.map(item => {
                const subtotal = (item.harga * item.jumlah) +
                    (item.extra?.reduce((t, e) => t + (e.harga * e.jumlah), 0) || 0);

                return {
                    pr_produk_id: item
                        .pr_produk_id, // pastikan field ini sesuai controller cek_voucher
                    subtotal: subtotal
                };
            });

            $("#btn-check-voucher").html("Memeriksa...").attr("disabled", true);
            $("#voucher-message").removeClass("text-danger text-success").text("");

            $.post(base_url + "kasir/cek_voucher", {
                kode_voucher: kode,
                // items: JSON.stringify(rinciOrderItems),
                items: JSON.stringify(itemsForVoucher),
                total: total
            }, function(res) {
                $("#btn-check-voucher").html("Cek Voucher").attr("disabled", false);

                if (res.status === 'success') {
                    $("#voucher-message")
                        .removeClass("text-danger")
                        .addClass("text-success")
                        .text(res.message);

                    $("#rinci-voucher").text(kode); // tampilkan kode voucher
                    $("#rinci-diskon").text("Rp " + res.diskon.toLocaleString("id-ID"));
                    $("#rinci-total").text(res.total_bayar.toLocaleString("id-ID"));

                    if (!$("#row-total-penjualan").length) {
                        $(".modal-body .row").append(`
                        <div class="col-md-12 text-right mt-2" id="row-total-penjualan">
                            <small class="text-muted">Total Penjualan: Rp ${res.total_penjualan.toLocaleString("id-ID")}</small>
                        </div>`);
                    } else {
                        $("#row-total-penjualan").html(
                            `<small class="text-muted">Total Penjualan: Rp ${res.total_penjualan.toLocaleString("id-ID")}</small>`
                        );
                    }

                    // ✅ Kunci input dan tombol
                    $("#input-voucher").attr("readonly", true);
                    $("#btn-check-voucher").prop("disabled", true).text("✔ Digunakan");

                } else {
                    // Jika error
                    $("#voucher-message")
                        .removeClass("text-success")
                        .addClass("text-danger")
                        .text(res.message);

                    $("#rinci-voucher").text("-");
                    $("#rinci-diskon").text("Rp 0");
                    $("#rinci-total").text(total.toLocaleString("id-ID"));
                    $("#row-total-penjualan").remove();


                }
            }, "json");
        });
        $("#reset-voucher").click(function() {
            $("#input-voucher").val("").attr("readonly", false);
            $("#btn-check-voucher").prop("disabled", false).text("Cek Voucher");
            $("#voucher-message").text("").removeClass("text-success text-danger");
            $("#rinci-voucher").text("-");
            $("#rinci-diskon").text("Rp 0");
            $("#row-total-penjualan").remove();
            // kamu bisa hitung ulang total jika mau
        });


        /////////////////////////
        $("#btn-buka-bayar").click(function() {
            const transaksi_id = $(this).data("id");

            // Ambil data transaksi terbaru dulu
            $.post(base_url + "kasir/get_detail_transaksi", {
                transaksi_id: transaksi_id
            }, function(res) {
                const total_tagihan = res.total_pembayaran || res.total_penjualan || 0;
                tampilkanModalPembayaran(transaksi_id, total_tagihan);
            }, "json");
        });


        // PEMBAYARAN
        function tampilkanModalPembayaran(transaksi_id, total_tagihan) {
            tagihanTotal = total_tagihan;

            // Ambil nilai diskon dari field (jika ada)
            const diskon = parseInt($("#rinci-diskon").text().replace(/\D/g, "")) || 0;
            const totalBayar = total_tagihan - diskon;

            pembayaranList = [];

            $("#bayar-transaksi-id").val(transaksi_id);
            $("#multi-tagihan").text("Rp " + totalBayar.toLocaleString("id-ID"));
            $("#multi-total-dibayar").text("Rp 0");
            $("#multi-sisa").text("Rp " + totalBayar.toLocaleString("id-ID"));
            $("#tabel-pembayaran-multi").html("");

            tagihanTotal = totalBayar;

            tambahPembayaran("1", "Cash", 0);
            $("#modalPembayaran").modal("show");
        }


        function tambahPembayaran(id, nama, jumlah = 0) {
            const index = pembayaranList.length;
            pembayaranList.push({
                metode_id: id,
                nama: nama,
                jumlah: jumlah,
                keterangan: ""
            });

            const row = `
                <tr>
                <td>${nama}</td>
                <td><input type="number" class="form-control input-jumlah" data-index="${index}" value="${jumlah}"></td>
                <td><input type="text" class="form-control input-ket" data-index="${index}"></td>
                <td><button class="btn btn-danger btn-sm btn-hapus-pembayaran" data-index="${index}">&times;</button></td>
                </tr>
            `;
            $("#tabel-pembayaran-multi").append(row);
            updateTotalMulti();
        }

        function updateTotalMulti() {
            let total = pembayaranList.reduce((sum, p) => sum + (parseInt(p.jumlah) || 0), 0);
            $("#multi-total-dibayar").text("Rp " + total.toLocaleString("id-ID"));
            let sisa = tagihanTotal - total;
            $("#multi-sisa").text("Rp " + sisa.toLocaleString("id-ID"));
        }

        $(document).on("input", ".input-jumlah", function() {
            const i = $(this).data("index");
            pembayaranList[i].jumlah = parseInt($(this).val()) || 0;
            updateTotalMulti();
        });

        $(document).on("input", ".input-ket", function() {
            const i = $(this).data("index");
            pembayaranList[i].keterangan = $(this).val();
        });

        $(document).on("click", ".btn-hapus-pembayaran", function() {
            const i = $(this).data("index");
            pembayaranList.splice(i, 1);
            $(this).closest("tr").remove();
            updateTotalMulti();
        });

        $("#btn-tambah-metode").click(function() {
            $.get(base_url + "kasir/get_metode_pembayaran", function(data) {
                const metode = JSON.parse(data);
                let html = "";
                metode.forEach(m => {
                    html +=
                        `<button class="btn btn-block btn-outline-dark btn-metode-pilih" data-id="${m.id}" data-nama="${m.metode_pembayaran}">${m.metode_pembayaran}</button>`;
                });
                $("#list-metode-pembayaran").html(html);
                $("#modalPilihMetode").modal("show");
            });
        });
        $(document).on("click", ".btn-metode-pilih", function() {
            const id = $(this).data("id");
            const nama = $(this).data("nama");
            tambahPembayaran(id, nama);
            $("#modalPilihMetode").modal("hide");
        });

        $(".btn-kalkulator").click(function() {
            const nominal = parseInt($(this).data("nominal"));
            let terakhir = pembayaranList.length - 1;
            pembayaranList[terakhir].jumlah += nominal;
            $(`.input-jumlah[data-index="${terakhir}"]`).val(pembayaranList[terakhir].jumlah);
            updateTotalMulti();
        });

        $("#formPembayaranMulti").submit(function(e) {
            e.preventDefault();

            const totalBayar = pembayaranList.reduce((sum, p) => sum + (parseInt(p.jumlah) || 0), 0);
            if (totalBayar < tagihanTotal) {
                alert("Total pembayaran masih kurang!");
                return;
            }

            const diskon = parseInt($("#rinci-diskon").text().replace(/\D/g, "")) || 0;
            const kode_voucher = $("#input-voucher").val().trim() || "";

            $.post(base_url + "kasir/simpan_pembayaran", {
                transaksi_id: $("#bayar-transaksi-id").val(),
                pembayaran: JSON.stringify(pembayaranList),
                kode_voucher: kode_voucher,
                diskon: diskon
            }, function(res) {
                alert(res.message);
                if (res.status === "success") {
                    $("#modalPembayaran").modal("hide");
                    $("#modalRincianPesanan").modal("hide"); // opsional
                    loadPendingOrders();
                }
            }, "json");
        });



        // function bukaPopupPembayaran(transaksi_id) {
        //     $.post(base_url + "kasir/get_detail_transaksi", {
        //         transaksi_id
        //     }, function(res) {
        //         // tampilkan popup pembayaran seperti gambar: metode, total, diskon, dll
        //         // isi field form hidden / visible untuk: transaksi_id, total, voucher, diskon

        //         // kamu bisa munculkan modal baru misalnya: #modalPembayaran
        //         // atau munculkan panel pembayaran di samping kanan halaman POS
        //     }, "json");
        // }


        // $(document).on("click", ".metode-bayar", function() {
        //     const metode_id = $(this).data("id");
        //     const nama = $(this).data("nama");

        //     const jumlah = prompt(`Masukkan jumlah untuk ${nama}:`);
        //     const jml = parseInt(jumlah);
        //     if (isNaN(jml) || jml <= 0) return;

        //     pembayaranList.push({
        //         metode_id,
        //         nama,
        //         jumlah: jml,
        //         keterangan: ""
        //     });
        //     updateTabelPembayaran();
        // });

        // function updateTabelPembayaran() {
        //     let html = "";
        //     let total = 0;
        //     pembayaranList.forEach((p, i) => {
        //         total += p.jumlah;
        //         html += `<tr>
        //         <td>${p.nama}</td>
        //         <td>Rp ${p.jumlah.toLocaleString()}</td>
        //         <td><input class="form-control form-control-sm" onchange="pembayaranList[${i}].keterangan = this.value"></td>
        //         <td><button class="btn btn-danger btn-sm" onclick="hapusPembayaran(${i})">×</button></td>
        //         </tr>`;
        //     });

        //     $("#tabelPembayaran").html(html);
        //     $("#totalPembayaran").text("Rp " + total.toLocaleString());
        // }

        // function hapusPembayaran(i) {
        //     pembayaranList.splice(i, 1);
        //     updateTabelPembayaran();
        // }

        // $("#formPembayaran").submit(function(e) {
        //     e.preventDefault();
        //     const total = pembayaranList.reduce((a, b) => a + b.jumlah, 0);
        //     if (total < totalTagihan) {
        //         alert("Total pembayaran kurang!");
        //         return;
        //     }

        //     $.post(base_url + "kasir/simpan_pembayaran", {
        //         transaksi_id: $("#bayar-transaksi-id").val(),
        //         pembayaran: JSON.stringify(pembayaranList)
        //     }, function(res) {
        //         alert(res.message);
        //         if (res.status === "success") {
        //             $("#modalPembayaran").modal("hide");
        //             $("#modalRincianPesanan").modal("hide");
        //             loadPendingOrders(); // refresh list
        //         }
        //     }, "json");
        // });


    });
    </script>

</body>

</html>