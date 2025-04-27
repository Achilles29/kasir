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
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon.ico">

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    }

    .container-fluid {
        display: flex;
        height: calc(100vh - 45px);
        /* dikurangi topmenu */
        background: #f5f0eb;
    }

    .sidebar {
        width: 270px;
        background: #6b0f1a;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 10px;
        border-right: 2px solid #f5f0eb;
        overflow: hidden;
        /* Fix utama: overflow hidden */
        position: relative;
        /* Tambah position relative */
    }

    .sidebar {
        width: 270px;
        background: #6b0f1a;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 10px;
        border-right: 2px solid #f5f0eb;
        overflow: hidden;
        /* Fix utama: overflow hidden */
        position: relative;
        /* Tambah position relative */
    }

    .sidebar h4 {
        color: #ffe7d6;
        font-weight: bold;
    }

    #pending-orders-container {
        flex-grow: 1;
        overflow-y: auto;
        margin-bottom: 100px;
        /* Beri ruang untuk tombol di bawah */
    }

    #menu-actions {
        position: absolute;
        /* Fix: absolute di dalam relative sidebar */
        bottom: 10px;
        /* Jarak dari bawah */
        width: calc(100% - 20px);
        /* Sesuai padding sidebar */
        background: #6b0f1a;
        /* Warna latar sama sidebar */
    }

    .main-content {
        display: flex;
        flex: 1;
    }

    .detail-pesanan {
        flex: 0 0 45%;
        padding: 10px;
        overflow-y: auto;
        background: #ffffff;
        border-right: 2px solid #f5f0eb;
    }

    .produk-pilihan {
        flex: 0 0 55%;
        padding: 10px;
        overflow-y: auto;
        background: #ffffff;
    }

    .kasir-content h4 {
        color: #6b0f1a;
        font-weight: bold;
    }

    .btn-primary,
    .btn-info,
    .btn-danger,
    .btn-warning {
        border: none;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #6b0f1a;
    }

    .btn-info {
        background-color: #a6482a;
    }

    .btn-danger {
        background-color: #c1121f;
    }

    .top-menu {
        height: 45px;
        background-color: #6b0f1a;
        color: #ffe7d6;
        display: flex;
        align-items: center;
        padding: 0 10px;
    }

    .top-menu a {
        color: #ffe7d6;
        margin-right: 15px;
        font-weight: bold;
    }



    /* Sidebar Pesanan Item */
    .pesanan-item {
        background: #ffffff;
        padding: 8px;
        margin-bottom: 8px;
        border-radius: 6px;
        color: #6b0f1a;
        border: 1px solid #eee;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pesanan-item:hover {
        background: #ffe7d6;
    }

    .pesanan-item.selected {
        background: #6b0f1a;
        color: #ffffff;
    }

    /* Tengah dan Kanan */
    .kasir-content {
        display: flex;
        flex: 1;
        background: #f5f0eb;
    }


    /* Tombol */
    .btn {
        font-size: 14px;
        border-radius: 6px;
    }

    /* Top bar */
    .top-menu {
        height: 45px;
        background: #6b0f1a;
        display: flex;
        align-items: center;
        padding: 0 10px;
        color: white;
    }


    .top-menu a:hover {
        text-decoration: underline;
    }

    /* Fullscreen Mode */
    /* body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    } */

    /* .container-fluid {
        display: flex;
        height: 100vh;
    } */

    /* Sidebar pesanan belum dibayar */
    /* .sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        background: #f8f9fa;
        padding: 10px;
        border-right: 1px solid #ddd;
    } */

    /* Container untuk pending orders dengan scrolling */
    /* #pending-orders-container {
        flex-grow: 1;
        overflow-y: auto;
        padding-bottom: 10px;
    } */

    /* Pending Orders */
    #pending-orders {
        max-height: 70vh;
        /* Batasi tinggi agar tidak menutupi tombol */
    }

    /* Menu tindakan tetap di bawah */
    /* #menu-actions {
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
        color: #fff !important;
        font-weight: bold;
    } */


    /* Area kasir utama */
    /* 
    .kasir-content {
        flex-grow: 1;
        padding: 10px;
        overflow-y: auto;
    } */

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


    /* Produk utama */
    .list-group-item.produk {
        font-weight: bold;
        border-bottom: 1px solid #eee;
        transition: background 0.3s;
    }

    /* Extra */
    .list-group-item.extra {
        background-color: #f8f9fa;
        padding-left: 2rem;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    /* Item batal (sudah void) */
    /* Produk batal (text-muted + background-light) */
    .list-group-item.batal {
        background-color: #f8f9fa;
        color: #6c757d;
        pointer-events: none;
    }

    /* Hover efek lebih soft */
    .list-group-item:hover {
        background-color: #f1f1f1;
    }

    /* Checkbox centang efek glow */
    input.checkbox-void:checked+label,
    .list-group-item input:checked {
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
    }

    /* Badge harga */
    .badge-produk {
        background-color: #007bff;
        color: white;
    }

    /* Warna badge harga extra */
    .badge-extra {
        background-color: #6c757d;
        color: white;
    }


    .badge-voided {
        background-color: #dc3545;
        /* merah VOIDED */
    }

    /* .top-menu {
        height: 45px;
        background-color: #f8f9fa;
    } */

    .top-menu .nav-item {
        display: inline-block;
    }

    .top-menu .nav-link {
        color: #555;
        font-size: 14px;
        text-decoration: none;
    }

    .top-menu .nav-link:hover {
        background-color: #e9ecef;
        border-radius: 5px;
    }

    .top-menu .nav-link i {
        margin-right: 5px;
    }
    </style>
</head>

<body>
    <!-- Top Tab Navigation -->
    <div class="top-menu bg-light d-flex align-items-center px-2" style="border-bottom: 1px solid #ddd;">
        <div class="nav-item">
            <a href="<?= base_url('beranda') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-home"></i> Beranda
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('kasir') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-cash-register"></i> POS
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('kasir/pesanan_terbayar') ?>" target="_blank"
                class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-chart-line"></i> Pesanan Terbayar
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('laporan') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-chart-line"></i> Laporan
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('stok') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-boxes"></i> Stok
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('member') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-users"></i> Member
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= base_url('setting') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-cogs"></i> Setting
            </a>
        </div>
    </div>

    <div class="container-fluid">

        <!-- Sidebar Pesanan Belum Dibayar -->
        <div class="sidebar">
            <h4>Pesanan Belum Dibayar</h4>

            <button id="btn-cetak-divisi" class="btn btn-info mt-2">Cetak per Divisi</button>

            <div id="pending-orders-container">
                <div id="pending-orders" class="mt-2"></div>
            </div>

            <div id="menu-actions">
                <button id="ubah-pesanan" class="btn btn-warning">Ubah Pesanan</button>
                <button id="rincian-pesanan" class="btn btn-info">Lihat Rincian</button>
                <button id="btnVoidPilihanModal" class="btn btn-danger">
                    <i class="fas fa-times-circle"></i> Void Pesanan
                </button>
            </div>
        </div>

        <!-- Area Utama (Tengah + Kanan) -->
        <div class="main-content">
            <div class="detail-pesanan">
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

                <h4>Total Harga: <span id="total-harga">Rp 0</span></h4>
                <h3>Total Bayar: <span id="total-bayar">Rp 0</span></h3>

                <button id="btn-batal" class="btn btn-danger">Batal</button>
                <input type="hidden" id="transaksi-id" value="">
                <button class="btn btn-warning" id="simpan-transaksi">Simpan Pesanan</button>
                <button class="btn btn-info" id="simpan-perubahan" style="display:none;">Simpan Perubahan</button>
            </div>

            <div class="produk-pilihan">
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
                                <button class="btn btn-danger" id="reset-voucher">Reset Voucher</button>
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

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Total Tagihan:</strong> <span id="rinci-tagihan">Rp 0</span></p>
                            <!-- ⬅️ Tambah -->
                            <p><strong>Total Diskon:</strong> <span id="rinci-diskon">Rp 0</span></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4><strong>Total Bayar:</strong> Rp <span id="rinci-total">0</span></h4>
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
                                        <div class="col-md-12">
                                            <p><strong>Total Penjualan:</strong> <span id="multi-total-penjualan">Rp
                                                    0</span></p>
                                            <p><strong>Diskon:</strong> <span id="multi-diskon">Rp 0</span></p>
                                            <p><strong>Total Tagihan:</strong> <span id="multi-tagihan">Rp 0</span></p>
                                            <p> <strong>Total DP:</strong> <span id="multi-dp">Rp 0</span></p>
                                            <p><strong>Total Dibayar:</strong> <span id="multi-total-dibayar">Rp
                                                    0</span></p>
                                            <p><strong>Sisa Pembayaran:</strong> <span id="multi-sisa">Rp 0</span></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <label>Input Cepat:</label><br>
                                        <div id="input-cepat-static" class="btn-group" role="group">
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="10000">10K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="20000">20K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="50000">50K</button>
                                            <button type="button" class="btn btn-light btn-kalkulator"
                                                data-nominal="100000">100K</button>
                                        </div>

                                        <div id="input-cepat-dinamis" class="mt-2">
                                            <!-- Tombol Rp Sisa akan muncul otomatis di sini -->
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


    <!-- Modal Void Pesanan -->
    <div class="modal fade" id="modalVoidPesanan" tabindex="-1" role="dialog" aria-labelledby="modalVoidPesananLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalVoidPesananLabel">Batalkan Pesanan</h5>
                    <button type="button" class="btn btn-light btn-sm ml-auto" id="btn-void-semua">
                        <i class="fas fa-ban"></i> Batalkan Semua
                    </button>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Void</th>
                            </tr>
                        </thead>
                        <tbody id="list-void-items"></tbody>
                        <div class="row px-3 py-2">
                            <div class="col">
                                <strong>Total Aktif: <span id="total-void-aktif">Rp 0</span></strong>
                            </div>
                            <div class="col text-right">
                                <strong>Total Batal: <span id="total-void-batal">Rp 0</span></strong>
                            </div>
                        </div>


                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Void Pilihan -->
    <div class="modal fade" id="modalVoidPilihan" tabindex="-1" aria-labelledby="modalVoidPilihanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVoidPilihanLabel">Pilih Produk/Extra yang Ingin Dibatalkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="formVoidPilihan">
                        <div id="listProdukVoid" class="mb-3">
                            <!-- List produk dan extra aktif, diisi via Ajax -->
                        </div>
                        <div class="mb-3">
                            <label for="alasanVoid" class="form-label">Alasan Void (Wajib)</label>
                            <input type="text" class="form-control" id="alasanVoid" name="alasan"
                                placeholder="Masukkan alasan void..." required>
                        </div>
                        <input type="hidden" id="transaksi_id_void" name="transaksi_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnVoidPilihan" class="btn btn-danger">Void Pilihan</button>
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
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        let extraData = {};
        let currentProdukId = null;
        let currentUID = null;
        let selectedOrderId = null;
        let deletedItems = [];
        let rinciOrderItems = [];
        let pembayaranList = [];
        let tagihanTotal = 0;
        let totalPenjualanAwal = 0; // ⬅️ simpan total penjualan awal

        function resetVoucherForm() {
            $("#input-voucher").val("").attr("readonly", false);
            $("#btn-check-voucher").prop("disabled", false).text("Cek Voucher");
            $("#voucher-message").text("").removeClass("text-success text-danger");
            $("#rinci-voucher").text("-");
            $("#rinci-diskon").text("Rp 0");
            $("#rinci-total").text("Rp 0");
            $("#row-total-penjualan").remove();
        }

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
                        subtotal += extra.harga * qty;
                    });
                }

                $(this).find(".total").text(formatRupiah(subtotal));
                total += subtotal;
            });
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

            // $("#order-list").append(row);
            $(row).hide().prependTo("#order-list").fadeIn();
            updateTotal();
        });
        $(document).on("input", ".qty", function() {
            const qty = parseInt($(this).val()) || 1;
            const harga = parseInt($(this).data("harga")) || 0;
            const uid = $(this).closest("tr").data("uid");

            let total = qty * harga;

            if (extraData[uid]) {
                extraData[uid].forEach(extra => {
                    total += extra.harga * qty; // ✅ ini penting
                });
            }

            $(this).closest("tr").find(".total").text(formatRupiah(total));
            refreshExtraList(uid); // ✅ Tambahkan ini!
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
                    jumlah: jumlahProduk // ✅ langsung ikut qty produk
                });
            });

            extraData[currentUID] = selected;

            const list = selected.map(e =>
                `<li>
            ➕ ${e.jumlah}x ${e.nama}
            <span class="float-right">Rp ${(e.harga * e.jumlah).toLocaleString('id-ID')}</span>
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
                        html += `
                        <div class="pesanan-item" data-id="${order.id}">
                            <div><strong>${order.no_transaksi}</strong></div>
                            <div>${order.customer}</div>
                            <div>Rp ${parseInt(order.total_penjualan).toLocaleString("id-ID")}</div>
                        </div>`;
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
                    // 1. Group by detail_unit_id
                    const grouped = {};

                    // 🔥 Tambahkan filter hanya item aktif (status null atau kosong)
                    const activeItems = res.items.filter(item => !item.status || item
                        .status === '');


                    activeItems.forEach((item) => {
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

        function refreshExtraList(uid) {
            const jumlahProduk = parseInt($(`[data-uid='${uid}']`).find(".qty").val()) || 1;

            const list = extraData[uid].map(e =>
                `<li>
    ➕ ${jumlahProduk}x ${e.nama}
    <span class="float-right">Rp ${(e.harga * jumlahProduk).toLocaleString('id-ID')}</span>
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

            $.post(base_url + "kasir/get_detail_transaksi_aktif", {
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

                totalPenjualanAwal = parseInt(res.total_penjualan || res.total_pembayaran || 0);
                $("#rinci-total").text(totalPenjualanAwal.toLocaleString("id-ID"));

                // ⬇️ Tambahan ini:
                $("#rinci-tagihan").text(totalPenjualanAwal.toLocaleString("id-ID"));

                // ⬅️ Tambahkan ini di SINI
                rinciOrderItems = res.items;

                // Grouping berdasarkan detail_unit_id
                const grouped = {};

                res.items.forEach(item => {
                    if (!grouped[item.detail_unit_id]) {
                        grouped[item.detail_unit_id] = {
                            nama_produk: item.nama_produk,
                            harga: parseInt(item.harga),
                            jumlah: 0,
                            extra: {},
                        };
                    }
                    grouped[item.detail_unit_id].jumlah += parseInt(item.jumlah);

                    // Gabungkan extra
                    if (item.extra?.length) {
                        item.extra.forEach(extra => {
                            const key = extra.nama; // berdasarkan nama
                            if (!grouped[item.detail_unit_id].extra[key]) {
                                grouped[item.detail_unit_id].extra[key] = {
                                    nama: extra.nama,
                                    harga: parseInt(extra.harga),
                                    jumlah: 0
                                };
                            }
                            grouped[item.detail_unit_id].extra[key].jumlah +=
                                parseInt(extra.jumlah);
                        });
                    }
                });


                let itemHtml = '';
                Object.values(grouped).forEach(item => {
                    itemHtml += `
        <tr>
            <td>${item.nama_produk}</td>
            <td class="text-center">Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td class="text-center">${item.jumlah}</td>
            <td class="text-right">Rp ${(item.harga * item.jumlah).toLocaleString('id-ID')}</td>
        </tr>`;

                    // Loop extra
                    if (item.extra) {
                        Object.values(item.extra).forEach(extra => {
                            itemHtml += `
                <tr class="text-muted small">
                    <td class="pl-4">➕ ${extra.nama}</td>
                    <td class="text-center">Rp ${extra.harga.toLocaleString('id-ID')}</td>
                    <td class="text-center">${extra.jumlah}</td>
                    <td class="text-right">Rp ${(extra.harga * extra.jumlah).toLocaleString('id-ID')}</td>
                </tr>`;
                        });
                    }
                });
                $("#rinci-item-list").html(itemHtml);


                // pastikan total bayar tampil benar juga
                $("#rinci-total").text(parseInt(res.total_pembayaran || res.total_penjualan ||
                    0).toLocaleString("id-ID"));

                $("#modalRincianPesanan").modal("show");

                // simpan transaksi_id ke tombol Bayar
                $("#btn-buka-bayar").data("id", transaksi_id);
            }, "json");
        });


        // Fungsi cek voucher
        $("#btn-check-voucher").click(function() {
            const kode = $("#input-voucher").val().trim();
            const total = totalPenjualanAwal; // ✅ GANTI, ambil dari total awal
            if (!kode || rinciOrderItems.length === 0) {
                alert("Mohon lengkapi data transaksi.");
                return;
            }

            const itemsForVoucher = rinciOrderItems.map(item => {
                return {
                    pr_produk_id: item.pr_produk_id,
                    subtotal: item.harga * item.jumlah
                };
            });

            $("#btn-check-voucher").html("Memeriksa...").attr("disabled", true);
            $("#voucher-message").removeClass("text-danger text-success").text("");

            $.post(base_url + "kasir/cek_voucher", {
                kode_voucher: kode,
                items: JSON.stringify(itemsForVoucher),
                total: total
            }, function(res) {
                $("#btn-check-voucher").html("Cek Voucher").attr("disabled", false);

                if (res.status === 'success') {
                    $("#voucher-message")
                        .removeClass("text-danger")
                        .addClass("text-success")
                        .text(res.message);

                    $("#rinci-voucher").text(kode);
                    $("#rinci-diskon").text("Rp " + res.diskon.toLocaleString("id-ID"));
                    $("#rinci-tagihan").text(res.total_penjualan.toLocaleString(
                        "id-ID")); // ✅ total sebelum diskon
                    $("#rinci-total").text(res.total_bayar.toLocaleString("id-ID"));

                    $("#input-voucher").attr("readonly", true);
                    $("#btn-check-voucher").prop("disabled", true).text("✔ Digunakan");

                } else {
                    $("#voucher-message")
                        .removeClass("text-success")
                        .addClass("text-danger")
                        .text(res.message);

                    $("#rinci-voucher").text("-");
                    $("#rinci-diskon").text("Rp 0");
                    $("#rinci-tagihan").text(totalPenjualanAwal.toLocaleString(
                        "id-ID")); // ⬅️ kembalikan tagihan asli
                    $("#rinci-total").text(totalPenjualanAwal.toLocaleString("id-ID"));

                    $("#row-total-penjualan").remove();
                }
            }, "json");
        });

        $("#reset-voucher").click(function() {
            $("#row-total-penjualan").remove();
            $("#input-voucher").val("").attr("readonly", false);
            $("#btn-check-voucher").prop("disabled", false).text("Cek Voucher");
            $("#voucher-message").text("").removeClass("text-success text-danger");
            $("#rinci-voucher").text("-");
            $("#rinci-diskon").text("Rp 0");
            $("#row-total-penjualan").remove();
            $("#rinci-total").text(totalPenjualanAwal.toLocaleString(
                "id-ID")); // ⬅️ KEMBALIKAN TOTAL BAYAR
        });


        /////////////////////////
        $("#btn-buka-bayar").click(function() {
            const transaksi_id = $(this).data("id");

            $.post(base_url + "kasir/get_detail_transaksi_aktif", {
                transaksi_id: transaksi_id
            }, function(res) {
                const total_penjualan = parseInt(res.total_penjualan) || 0;

                // Ambil diskon dari halaman rincian pesanan
                const diskonText = $("#rinci-diskon").text();
                const diskon = parseInt(diskonText.replace(/\D/g, "")) || 0;

                const total_tagihan = total_penjualan - diskon;
                const total_dp = parseInt(res.total_pembayaran) || 0;

                tampilkanModalPembayaran(transaksi_id, total_tagihan, total_penjualan, diskon,
                    total_dp, res.pembayaran);
            }, "json");
        });




        // PEMBAYARAN
        function tampilkanModalPembayaran(transaksi_id, total_tagihan, total_penjualan, diskon, total_dp = 0,
            pembayaranSebelumnya = []) {
            $("#row-total-penjualan").remove();

            tagihanTotal = total_tagihan;
            pembayaranList = [];

            $("#bayar-transaksi-id").val(transaksi_id);

            $("#multi-total-penjualan").text("Rp " + total_penjualan.toLocaleString("id-ID"));
            $("#multi-diskon").text("Rp " + diskon.toLocaleString("id-ID"));
            $("#multi-tagihan").text("Rp " + total_tagihan.toLocaleString("id-ID"));
            $("#multi-dp").text("Rp " + total_dp.toLocaleString("id-ID"));
            $("#multi-total-dibayar").text("Rp 0");
            $("#multi-sisa").text("Rp " + (total_tagihan - total_dp).toLocaleString("id-ID"));
            $("#tabel-pembayaran-multi").html("");

            // 🔥 Tambahkan semua pembayaran sebelumnya
            if (pembayaranSebelumnya.length > 0) {
                pembayaranSebelumnya.forEach(p => {
                    tambahPembayaran(p.metode_id, p.metode_nama || "Metode " + p.metode_id, p.jumlah);
                });
            } else {
                tambahPembayaran("1", "Cash", 0); // kalau tidak ada pembayaran sebelumnya, default cash
            }

            $("#modalPembayaran").modal("show");
        }


        function tambahPembayaran(id, nama, jumlah = 0) {
            // Cek apakah sudah ada metode pembayaran ini
            const existingIndex = pembayaranList.findIndex(p => p.metode_id == id);

            if (existingIndex !== -1) {
                // Jika sudah ada, tambahkan jumlahnya
                pembayaranList[existingIndex].jumlah += jumlah;

                // Update input jumlah yang sudah ada di tabel
                const inputJumlah = $(`.input-jumlah[data-index="${existingIndex}"]`);
                let currentVal = parseInt(inputJumlah.val()) || 0;
                inputJumlah.val(currentVal + jumlah);

                updateTotalMulti();
            } else {
                // Jika belum ada, tambahkan baris baru
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
        }


        function updateTotalMulti() {
            let total = pembayaranList.reduce((sum, p) => sum + (parseInt(p.jumlah) || 0), 0);
            $("#multi-total-dibayar").text("Rp " + total.toLocaleString("id-ID"));

            let dp = parseInt($("#multi-dp").text().replace(/\D/g, "")) || 0;
            let sisa = tagihanTotal - total;

            $("#multi-sisa").text("Rp " + sisa.toLocaleString("id-ID"));

            if (sisa > 0) {
                let html = `
            <button type="button" class="btn btn-info btn-kalkulator" data-nominal="${sisa}">
                Rp ${sisa.toLocaleString('id-ID')}
            </button>
        `;
                $("#input-cepat-dinamis").html(html);
            } else {
                $("#input-cepat-dinamis").empty();
            }
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

        $(document).on("click", ".btn-kalkulator", function() {
            const nominal = parseInt($(this).data("nominal"));
            let terakhir = pembayaranList.length - 1;
            pembayaranList[terakhir].jumlah += nominal;
            $(`.input-jumlah[data-index="${terakhir}"]`).val(pembayaranList[terakhir].jumlah);
            updateTotalMulti();
        });


        $("#formPembayaranMulti").submit(function(e) {
            e.preventDefault();

            const totalBayar = pembayaranList.reduce((sum, p) => sum + (parseInt(p.jumlah) || 0), 0);

            const diskon = parseInt($("#multi-diskon").text().replace(/\D/g, "")) || 0;
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
                    $("#modalRincianPesanan").modal("hide");
                    loadPendingOrders();
                    resetVoucherForm();
                }
            }, "json");
        });




        /////////////////
        ///// VOID MODEL LAMA //////
        /////////////////
        // Klik Batalkan Pesanan
        $("#batalkan-pesanan").click(function() {
            if (!selectedOrderId) {
                Swal.fire("Pilih pesanan dahulu!", "", "warning");
                return;
            }

            $.post(base_url + "kasir/get_detail_transaksi", {
                transaksi_id: selectedOrderId
            }, function(res) {
                tampilkanModalVoid(res);
            }, "json");
        });

        // Tampilkan Modal Void
        function tampilkanModalVoid(data) {
            let html = '';
            let totalAktif = 0;
            let totalBatal = 0;

            const grouped = {};

            // Grouping berdasarkan detail_unit_id
            data.items.forEach(item => {
                if (!grouped[item.detail_unit_id]) {
                    grouped[item.detail_unit_id] = {
                        produk: item,
                        extras: []
                    };
                }
                if (item.extra && item.extra.length > 0) {
                    grouped[item.detail_unit_id].extras.push(...item.extra);
                }
            });

            // Looping semua produk
            Object.values(grouped).forEach(group => {
                const produk = group.produk;
                const isProdukBatal = produk.status === 'BATAL';
                const hargaTotal = produk.harga * produk.jumlah;

                if (isProdukBatal) {
                    totalBatal += hargaTotal;
                } else {
                    totalAktif += hargaTotal;
                }

                html += `
            <tr id="row-void-${produk.id}" class="${isProdukBatal ? 'bg-light text-muted' : ''}" data-id="${produk.id}">
                <td>${produk.nama_produk}</td>
                <td class="text-center">${produk.jumlah}</td>
                <td class="text-center">Rp ${parseInt(produk.harga).toLocaleString('id-ID')}</td>
                <td class="text-center">
                    ${isProdukBatal 
                        ? `<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-ban"></i></button>`
                        : `<button class="btn btn-danger btn-sm btn-void-item" data-id="${produk.id}" data-nama="${produk.nama_produk}"><i class="fas fa-trash"></i></button>`
                    }
                </td>
            </tr>`;

                // Lalu tampilkan semua extra milik produk ini
                if (group.extras && group.extras.length > 0) {
                    group.extras.forEach(extra => {
                        const isExtraBatal = extra.status === 'BATAL';
                        const hargaExtraTotal = extra.harga * extra.jumlah;

                        if (isExtraBatal) {
                            totalBatal += hargaExtraTotal;
                        } else {
                            totalAktif += hargaExtraTotal;
                        }

                        html += `
                    <tr class="text-muted small ${isExtraBatal ? 'bg-light' : ''}" id="row-void-extra-${extra.id}">
                        <td class="pl-4">➕ ${extra.nama}</td>
                        <td class="text-center">${extra.jumlah}</td>
                        <td class="text-center">Rp ${parseInt(extra.harga).toLocaleString('id-ID')}</td>
                        <td class="text-center">
                            ${isExtraBatal
                                ? `<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-ban"></i></button>`
                                : `<button class="btn btn-warning btn-sm btn-void-extra" data-id="${extra.id}" data-nama="${extra.nama}">
                                    <i class="fas fa-trash"></i></button>`
                            }
                        </td>
                    </tr>`;
                    });
                }
            });

            $("#list-void-items").html(html);

            // Update total
            $("#total-void-aktif").text("Rp " + totalAktif.toLocaleString('id-ID'));
            $("#total-void-batal").text("Rp " + totalBatal.toLocaleString('id-ID'));

            $("#modalVoidPesanan").modal("show");
        }



        // Klik Void per Produk
        $(document).on("click", ".btn-void-item", function() {
            const detail_id = $(this).data("id");
            const nama = $(this).data("nama");

            Swal.fire({
                title: `Void Produk`,
                html: `Masukkan alasan void untuk <b>"${nama}"</b>`,
                input: 'text',
                inputPlaceholder: 'Alasan void...',
                showCancelButton: true,
                confirmButtonText: 'Void Produk',
                cancelButtonText: 'Batal',
                backdrop: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                focusConfirm: false,
                willOpen: () => {
                    document.activeElement.blur();
                },
                preConfirm: (alasan) => {
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan wajib diisi');
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const alasan = result.value;
                    $.post(base_url + "kasir/void_item", {
                        detail_id: detail_id,
                        alasan: alasan
                    }, function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Produk berhasil di-void. Cetak struk void?",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Cetak",
                                cancelButtonText: "Tidak"
                            }).then((choice) => {
                                if (choice.isConfirmed) {
                                    $.post(base_url +
                                        "kasir/cetak_void_internal", {
                                            void_ids: [res.void_id]
                                        },
                                        function(cetakres) {
                                            console.log(cetakres.message);
                                        }, "json");
                                }
                                loadPendingOrders(); // Tetap refresh pesanan
                            });

                            // Disable tombol void produk
                            const $row = $(`#row-void-${detail_id}`);
                            $row.find('button').removeClass('btn-danger').addClass(
                                'btn-secondary').attr('disabled', true).html(
                                '<i class="fas fa-ban"></i>');
                            $row.addClass('bg-light text-muted');

                            // Disable semua extra di bawahnya
                            $row.nextUntil("tr:not(.text-muted)").find('button')
                                .removeClass('btn-warning')
                                .addClass('btn-secondary')
                                .attr('disabled', true)
                                .html('<i class="fas fa-ban"></i>');
                            $row.nextUntil("tr:not(.text-muted)").addClass(
                                'bg-light text-muted');
                        } else {
                            Swal.fire("Gagal", res.message, "error");
                        }
                    }, "json");
                }
            });
        });


        $(document).on("click", ".btn-void-extra", function() {
            const extra_id = $(this).data("id");
            const nama = $(this).data("nama");

            Swal.fire({
                title: `Void Extra`,
                html: `Masukkan alasan void untuk <b>"${nama}"</b>`,
                input: 'text',
                inputPlaceholder: 'Alasan void...',
                showCancelButton: true,
                confirmButtonText: 'Void Extra',
                cancelButtonText: 'Batal',
                backdrop: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                focusConfirm: false,
                willOpen: () => {
                    document.activeElement.blur();
                },
                preConfirm: (alasan) => {
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan wajib diisi');
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const alasan = result.value;
                    $.post(base_url + "kasir/void_extra_item", {
                        extra_id: extra_id,
                        alasan: alasan
                    }, function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Extra berhasil di-void. Cetak struk void?",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Cetak",
                                cancelButtonText: "Tidak"
                            }).then((choice) => {
                                if (choice.isConfirmed) {
                                    $.post(base_url +
                                        "kasir/cetak_void_internal", {
                                            void_ids: [res.void_id]
                                        },
                                        function(cetakres) {
                                            console.log(cetakres.message);
                                        }, "json");
                                }
                                loadPendingOrders();
                            });

                            // Disable tombol void extra
                            const $row = $(`#row-void-extra-${extra_id}`);
                            $row.find('button').removeClass('btn-warning').addClass(
                                'btn-secondary').attr('disabled', true).html(
                                '<i class="fas fa-ban"></i>');
                            $row.addClass('bg-light text-muted');
                        } else {
                            Swal.fire("Gagal", res.message, "error");
                        }
                    }, "json");
                }
            });
        });






        // Klik Batalkan Semua
        $("#btn-void-semua").click(function() {
            Swal.fire({
                title: 'Batalkan Semua Pesanan?',
                text: "Semua produk akan dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(base_url + "kasir/void_semua", {
                        transaksi_id: selectedOrderId
                    }, function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Semua produk berhasil di-void. Cetak struk void?",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Cetak",
                                cancelButtonText: "Tidak"
                            }).then((choice) => {
                                if (choice.isConfirmed) {
                                    $.post(base_url +
                                        "kasir/cetak_void_internal", {
                                            void_ids: res.void_ids
                                        },
                                        function(cetakres) {
                                            console.log(cetakres.message);
                                        }, "json");
                                }
                                loadPendingOrders();
                            });

                            $("#modalVoidPesanan").modal("hide");
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }, "json");
                }
            });
        });




        ///// VOID MODEL BARU //////

        $("#btnVoidPilihanModal").click(function() {
            if (!selectedOrderId) {
                Swal.fire("Pilih pesanan dulu", "Silakan pilih pesanan yang ingin di-void", "info");
                return;
            }
            openVoidPilihan(selectedOrderId);
        });

        // Buka modal Void Pilihan
        function openVoidPilihan(transaksi_id) {
            $("#transaksi_id_void").val(transaksi_id);
            $("#listProdukVoid").html('<i>Loading...</i>');

            $.post(base_url + "kasir/get_detail_transaksi_aktif", {
                transaksi_id: transaksi_id
            }, function(res) {
                if (res.items && res.items.length > 0) {
                    let html = `
                <div class="d-flex justify-content-between mb-2">
                    <button type="button" id="selectAllProduk" class="btn btn-outline-primary btn-sm">Pilih Semua Produk</button>
                    <button type="button" id="selectAllExtra" class="btn btn-outline-secondary btn-sm">Pilih Semua Extra</button>
                    <button type="button" id="uncheckAll" class="btn btn-outline-secondary btn-sm">Uncheck Semua</button>
                </div>
                <div class="list-group" id="list-group-void">
            `;

                    res.items.forEach(function(item) {
                        const isBatal = item.status === 'batal';
                        const disabled = isBatal ? 'disabled checked' : '';
                        const classMuted = isBatal ? 'batal' : 'produk';

                        html += `
                    <label class="list-group-item d-flex justify-content-between align-items-center ${classMuted}">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-2 checkbox-void" type="checkbox" ${disabled} value="${item.id}" data-type="produk" id="produk-${item.id}">
                            <span>${item.jumlah}x ${item.nama_produk}</span>
                        </div>
                        <span class="badge badge-produk rounded-pill">${formatRupiah(item.harga * item.jumlah)}</span>
                    </label>
                `;

                        if (item.extra && item.extra.length > 0) {
                            item.extra.forEach(function(extra) {
                                html += `
                            <label class="list-group-item d-flex justify-content-between align-items-center extra">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2 checkbox-void" type="checkbox" value="${extra.id}" data-type="extra" data-parent-id="${item.id}" id="extra-${extra.id}">
                                    <small>➔ ${extra.nama}</small>
                                </div>
                                <span class="badge badge-extra rounded-pill">${formatRupiah(extra.harga * extra.jumlah)}</span>
                            </label>
                        `;
                            });
                        }
                    });

                    html += '</div>';
                    $("#listProdukVoid").html(html);

                    // Scroll otomatis ke bawah setelah render
                    setTimeout(() => {
                        const listGroup = document.getElementById('list-group-void');
                        if (listGroup) {
                            listGroup.scrollTop = listGroup.scrollHeight;
                        }
                    }, 200);
                } else {
                    $("#listProdukVoid").html('<i>Tidak ada produk yang aktif.</i>');
                }
            }, "json");

            $("#modalVoidPilihan").modal("show");
        }

        // Tombol pilih semua produk
        $(document).on('click', '#selectAllProduk', function() {
            $(".checkbox-void").each(function() {
                if ($(this).data('type') === 'produk' && !$(this).is(':disabled')) {
                    $(this).prop('checked', true);
                }
            });
        });

        // Tombol pilih semua extra
        $(document).on('click', '#selectAllExtra', function() {
            $(".checkbox-void").each(function() {
                if ($(this).data('type') === 'extra' && !$(this).is(':disabled')) {
                    $(this).prop('checked', true);
                }
            });
        });
        // Tombol Uncheck Semua
        $(document).on('click', '#uncheckAll', function() {
            $(".checkbox-void").each(function() {
                if (!$(this).is(':disabled')) {
                    $(this).prop('checked', false);
                }
            });
        });


        // centang produk dan extra        
        $(document).on('change', '.checkbox-void', function() {
            const id = $(this).val();
            const isChecked = $(this).is(':checked');
            const type = $(this).data('type');

            // Kalau produk utama dicentang, extra ikut
            if (type === 'produk') {
                $(`input[data-parent-id='${id}']`).prop('checked', isChecked);
            }
        });


        // Submit Void Pilihan
        $("#btnVoidPilihan").click(function() {
            const selected = [];
            $(".checkbox-void:checked").each(function() {
                selected.push({
                    id: $(this).val(),
                    type: $(this).data("type")
                });
            });

            const alasan = $("#alasanVoid").val();
            const transaksi_id = $("#transaksi_id_void").val();

            if (selected.length === 0) {
                Swal.fire("Error", "Pilih minimal 1 produk atau extra untuk void.", "error");
                return;
            }

            if (!alasan.trim()) {
                Swal.fire("Error", "Alasan void wajib diisi.", "error");
                return;
            }

            $.post(base_url + "kasir/void_pilihan", {
                transaksi_id: transaksi_id,
                items: JSON.stringify(selected),
                alasan: alasan
            }, function(res) {
                if (res.status === 'success') {
                    $("#modalVoidPilihan").modal("hide");

                    Swal.fire({
                        title: 'Cetak Struk Void?',
                        text: 'Apakah ingin mencetak struk void untuk pesanan ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Cetak Sekarang',
                        cancelButtonText: 'Nanti'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cetakVoidPilihan(res.void_ids); // void_ids baru
                        }
                    });

                    loadPendingOrders();
                } else {
                    Swal.fire("Gagal", res.message, "error");
                }
            }, "json");
        });

        function cetakVoidPilihan(void_ids) {
            $.post(base_url + "kasir/cetak_void_internal", {
                void_ids: void_ids
            }, function(res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success');
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }, "json");
        }


        // // Kirim ke cetak_void_internal
        // function cetakVoidPilihan(void_ids) {
        //     $.post(base_url + "kasir/cetak_void_internal", {
        //         void_ids: void_ids
        //     }, function(res) {
        //         if (res.status === 'success') {
        //             Swal.fire('Berhasil', res.message, 'success');
        //         } else {
        //             Swal.fire('Gagal', res.message, 'error');
        //         }
        //     }, "json");
        // }




    });
    </script>

</body>

</html>