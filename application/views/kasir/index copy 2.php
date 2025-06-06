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
        display: flex;
        flex-direction: column;
        height: calc(100vh - 45px);
        /* 100% tinggi minus top bar */
        width: 270px;
        background: #6b0f1a;
        padding: 10px;
        color: white;
        border-right: 2px solid #f5f0eb;
    }


    #pending-orders-container {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 120px;
        /* Tambahkan padding agar space aman! */
    }

    #btn-cetak-divisi {
        display: block;
        margin: 10px auto;
        /* tengah otomatis */
        width: 80%;
        /* atau 100% kalau mau full */
        text-align: center;
    }

    #menu-actions {
        margin-top: auto;
        padding-top: 10px;
        padding-bottom: 50px;

    }

    /* #menu-actions {
        bottom: 10px;
    } */

    .sidebar h4 {
        color: #ffe7d6;
        font-weight: bold;
    }


    .main-content {
        display: flex;
        flex: 1;
    }

    .detail-pesanan {
        flex: 0 0 45%;
        padding: 10px;
        overflow-y: auto;
        background: #f5f0eb;
        /* ← GANTI jadi krem */
        border-right: 2px solid #f5f0eb;
    }

    .produk-pilihan {
        flex: 0 0 55%;
        padding: 10px;
        overflow-y: auto;
        background: #f5f0eb;
        /* ← GANTI jadi krem */
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


    /* Pending Orders */
    #pending-orders {
        max-height: 70vh;
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

    #search {
        position: relative;
        padding-right: 30px;
        /* Biar ada ruang untuk tombol X */
    }

    /* Tombol clear */
    #clear-search {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        display: none;
        /* Default sembunyikan */
    }

    /* Modal Tutup Shift */
    #modalTutupShift .modal-content {
        border-radius: 10px;
    }

    #modalTutupShift .modal-body {
        padding: 20px 30px;
        font-size: 14px;
    }

    #modalTutupShift hr {
        margin: 10px 0;
    }

    #list-metode-pembayaran-shift {
        margin-left: 10px;
    }

    #list-metode-pembayaran-shift .d-flex {
        justify-content: space-between;
        padding: 2px 0;
    }

    #modalTutupShift .small {
        font-size: 13px;
        color: #555;
    }

    #modalTutupShift strong.text-danger {
        font-size: 18px;
    }

    .voucher-card {
        border: 1px solid #ddd;
        border-left: 6px solid #6b0f1a;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        margin-bottom: 10px;
        background-color: #fff;
        transition: 0.2s;
    }

    .voucher-card:hover {
        background-color: #f9f0eb;
        transform: scale(1.01);
    }

    .voucher-logo {
        height: 50px;
        width: 50px;
        margin-right: 15px;
    }

    .voucher-info {
        flex-grow: 1;
    }

    .voucher-info h5 {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
        color: #6b0f1a;
    }

    .voucher-info p {
        margin: 2px 0;
        font-size: 13px;
        color: #555;
    }

    .btn-use-voucher {
        background-color: #6b0f1a;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        border: none;
    }

    .btn-outline-maroon {
        color: #800000;
        border-color: #800000;
    }

    .btn-outline-maroon:hover {
        background-color: #800000;
        color: #fff;
    }

    .btn-maroon {
        background-color: #800000;
        color: white;
        border: none;
    }

    .btn-maroon:hover {
        background-color: #a01c1c;
    }

    .modal-header {
        background: linear-gradient(45deg, #800000, #cc5a00);
        padding: 1rem 1.5rem;
        align-items: center;
    }

    .modal-title {
        font-weight: bold;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .summary-box {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    #input-cepat-dinamis .btn {
        margin: 0 5px 5px 0;
    }

    .btn-extra[disabled] {
        background-color: #6c757d !important;
        opacity: 0.7;
        cursor: not-allowed;
    }

    #kosongkan-keranjang {
        background-color: #f0ad4e;
        border: none;
    }
    </style>
</head>

<body>
    <!-- Top Tab Navigation -->
    <div class="top-menu bg-light d-flex align-items-center px-2" style="border-bottom: 1px solid #ddd;">
        <div class="nav-item">
            <button id="btn-sync-data-umum" class="btn btn-warning font-weight-bold mx-2 my-1">
                🔄 Sinkronisasi Data Umum
            </button>
        </div>

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
            <a href="<?= base_url('kasir/transaksi_pending') ?>" target="_blank"
                class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-list"></i> Transaksi Pending
            </a>
        </div>

        <div class="nav-item">
            <a href="<?= base_url('kasir/pesanan_terbayar') ?>" target="_blank"
                class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-chart-line"></i> Pesanan Terbayar
            </a>
        </div>
        <!-- <div class="nav-item">
            <a href="<?= base_url('laporan') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-chart-line"></i> Laporan
            </a>
        </div> -->
        <!-- <div class="nav-item">
            <a href="<?= base_url('stok') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
                <i class="fas fa-boxes"></i> Stok
            </a>
        </div> -->
        <div class="nav-item">
            <a href="<?= base_url('customer') ?>" target="_blank" class="nav-link small font-weight-bold px-3 py-2">
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
            <div id="pending-orders-container">
                <h4>Pesanan Belum Dibayar</h4>
                <button id="btn-cetak-divisi" class="btn btn-info mt-2">Cetak per Divisi</button>
                <div class="mt-2 px-2">
                    <input type="text" id="searchPendingOrder" class="form-control form-control-sm"
                        placeholder="Cari nama customer/meja...">
                </div>

                <div id="pending-orders" class="mt-2"></div>

            </div>

            <div id="menu-actions">
                <button id="ubah-pesanan" class="btn btn-secondary btn-block">Ubah Pesanan</button>
                <button id="rincian-pesanan" class="btn btn-success btn-block">Lihat Rincian/Bayar</button>
                <button id="kosongkan-keranjang" class="btn btn-warning btn-block">
                    <i class="fas fa-trash"></i> Kosongkan Keranjang
                </button>

                <button id="btnVoidPilihanModal" class="btn btn-danger btn-block">
                    <i class="fas fa-times-circle"></i> Void Pesanan
                </button>
                <button class="btn btn-danger btn-block" id="btnTutupShift">
                    <i class="fas fa-door-closed"></i> Tutup Shift
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
                <!-- Tombol Simpan Transaksi Baru -->
                <button id="simpan-transaksi" class="btn btn-primary">
                    <span id="spinner-transaksi" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                    <span id="text-transaksi">Simpan Pesanan</span>
                </button>

                <!-- Tombol Simpan Perubahan -->
                <button id="simpan-perubahan" class="btn btn-success">
                    <span id="spinner-perubahan" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                    <span id="text-perubahan">Simpan Perubahan</span>
                </button>

            </div>

            <div class="produk-pilihan">
                <h4>Cari Produk</h4>
                <div style="position: relative;">
                    <input type="text" id="search" class="form-control" placeholder="Cari produk...">
                    <span id="clear-search"><i class="fas fa-times"></i></span>
                </div>

                <h4 class="mt-3">Divisi</h4>
                <div id="divisi-tab">
                    <button class="btn btn-outline-dark active" data-divisi="">SEMUA</button>
                    <?php foreach ($divisi as $d): ?>
                    <button class="btn btn-outline-dark" data-divisi="<?= $d['id']; ?>">
                        <?= $d['nama_divisi']; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <!-- <h4 class="mt-3">Kategori</h4>
                <div id="kategori-tab">
                    <button class="btn btn-outline-dark active" data-kategori="">Semua</button>
                    <?php foreach ($kategori as $k): ?>
                    <button class="btn btn-outline-dark" data-kategori="<?= $k['id']; ?>">
                        <?= $k['nama_kategori']; ?>
                    </button>
                    <?php endforeach; ?>
                </div> -->

                <h4 class="mt-3">Daftar Produk</h4>
                <div class="row" id="produk-list"></div>
            </div>
        </div>

    </div>
    <!-- Modal Input Modal Awal -->
    <div class="modal fade" id="modalAwalKasir" tabindex="-1" aria-labelledby="modalAwalKasirLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formModalAwal">
                <div class="modal-content">
                    <div class="modal-header bg-maroon text-white">
                        <h5 class="modal-title" id="modalAwalKasirLabel">Input Modal Awal Kasir</h5>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Keterangan Shift</label>
                            <select class="form-control" id="shift-keterangan" name="keterangan">
                                <option value="SHIFT 1">SHIFT 1</option>
                                <option value="SHIFT 2">SHIFT 2</option>
                                <option value="SHIFT 3">SHIFT 3</option>
                                <option value="SHIFT 4">SHIFT 4</option>
                                <option value="SHIFT 5">SHIFT 5</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Modal Awal (Rp)</label>
                            <input type="number" step="0.01" class="form-control" id="modal_awal" name="modal_awal"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Mulai Shift</button>
                    </div>
                </div>
            </form>
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
                        <label>Voucher:</label>

                        <div class="input-group">
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalVoucherList">
                                Pilih Voucher
                            </button>

                            <input type="text" id="input-voucher" class="form-control"
                                placeholder="Klik tombol pilih voucher" readonly>
                            <div class="input-group-append">

                                <button class="btn btn-primary" id="btn-check-voucher">Cek Voucher</button>
                                <button class="btn btn-danger" id="reset-voucher">Reset</button>
                            </div>
                        </div>
                    </div>
                    <small id="voucher-message" class="text-success"></small>

                    <!-- Modal Daftar Voucher -->
                    <div class="modal fade" id="modalVoucherList" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <!-- ⬇️ Ganti header dengan ini -->
                                <div
                                    class="modal-header bg-maroon text-white d-flex align-items-center justify-content-between">
                                    <h5 class="modal-title mb-0">Pilih Voucher</h5>
                                    <input type="text" id="searchVoucher" class="form-control ml-3"
                                        placeholder="🔍 Cari kode voucher..." style="max-width: 300px;">
                                </div>
                                <!-- end header -->

                                <div class="modal-body" id="voucher-list-container">
                                    <!-- Voucher cards akan ditampilkan di sini -->
                                </div>
                            </div>
                        </div>
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
                    <div class="modal-header text-white" style="background: linear-gradient(45deg, #800000, #cc5a00);">
                        <h5 class="modal-title"><span>💰</span> Menyelesaikan Penjualan</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Bagian Metode -->
                            <div class="col-md-4">
                                <h5 class="font-weight-bold mb-3">Metode Pembayaran</h5>
                                <div id="metode-pembayaran-list"
                                    class="list-group shadow-sm bg-white rounded border p-2">
                                    <!-- Akan diisi default TUNAI via JS -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-maroon mt-3" id="btn-tambah-metode">
                                    + Tambah Metode
                                </button>
                            </div>

                            <!-- Bagian Input -->
                            <div class="col-md-8">
                                <h5>Input Pembayaran</h5>
                                <table class="table table-sm table-bordered table-hover rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembayaran-multi" class="align-middle text-sm"></tbody>
                                </table>
                                <div class="row mb-2">
                                    <div class="col">
                                        <!-- <div class="summary-box text-sm">
                                            <p><strong>Total Penjualan:</strong> Rp 87.000</p>
                                            <p><strong>Diskon:</strong> Rp 0</p>
                                            <p><strong>Total Tagihan:</strong> Rp 87.000</p>
                                            <p><strong>Total DP:</strong> Rp 0</p>
                                            <p><strong>Total Dibayar:</strong> Rp 0</p>
                                            <p class="text-danger"><strong>Sisa Pembayaran:</strong> Rp 87.000</p>
                                        </div> -->
                                        <div class="summary-box text-sm">
                                            <p><strong>Total Penjualan:</strong> <span id="multi-total-penjualan">Rp
                                                    0</span></p>
                                            <p><strong>Diskon:</strong> <span id="multi-diskon">Rp 0</span></p>
                                            <p><strong>Total Tagihan:</strong> <span id="multi-tagihan">Rp 0</span></p>
                                            <p><strong>Total DP:</strong> <span id="multi-dp">Rp 0</span></p>
                                            <p><strong>Total Dibayar:</strong> <span id="multi-total-dibayar">Rp
                                                    0</span></p>
                                            <p class="text-danger"><strong>Sisa Pembayaran:</strong> <span
                                                    id="multi-sisa">Rp 0</span></p>
                                        </div>


                                    </div>
                                    <div class="col text-right">
                                        <label>Input Cepat:</label><br>
                                        <div class="btn-group btn-group-sm mb-2" role="group" aria-label="Input Cepat">
                                            <button type="button" class="btn btn-outline-secondary btn-kalkulator"
                                                data-nominal="10000">10K</button>
                                            <button type="button" class="btn btn-outline-secondary btn-kalkulator"
                                                data-nominal="20000">20K</button>
                                            <button type="button" class="btn btn-outline-secondary btn-kalkulator"
                                                data-nominal="50000">50K</button>
                                            <button type="button" class="btn btn-outline-secondary btn-kalkulator"
                                                data-nominal="100000">100K</button>
                                        </div>

                                        <div id="input-cepat-dinamis" class="mt-2">
                                            <!-- Tombol Rp Sisa akan muncul otomatis di sini -->
                                        </div>

                                    </div>
                                </div>
                                <input type="hidden" name="transaksi_id" id="bayar-transaksi-id">
                                <button type="submit" class="btn btn-maroon btn-lg btn-block mt-3"
                                    id="btn-submit-bayar">
                                    <span id="spinner-bayar" class="spinner-border spinner-border-sm d-none"
                                        role="status" aria-hidden="true"></span>
                                    <span id="text-bayar">✔ Selesaikan Pembayaran</span>
                                </button>

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
    <!-- <div class="modal fade" id="modalVoidPesanan" tabindex="-1" role="dialog" aria-labelledby="modalVoidPesananLabel"
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
    </div> -->

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
                    <button id="btnVoidPilihan" class="btn btn-danger">
                        <span class="spinner-border spinner-border-sm d-none" id="spinnerVoid" role="status"
                            aria-hidden="true"></span>
                        Void Pilihan
                    </button>
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

    <!-- Modal Tutup Shift -->
    <!-- Modal Tutup Shift -->
    <div class="modal fade" id="modalTutupShift" tabindex="-1" role="dialog" aria-labelledby="modalTutupShiftLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="formTutupShift">
                <div class="modal-content rounded-3 border-0 shadow">

                    <div class="modal-header bg-danger text-white rounded-top">
                        <h5 class="modal-title" id="modalTutupShiftLabel">Tutup Shift Kasir</h5>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3 row font-weight-bold">
                            <div class="col-6">KASIR:</div>
                            <div class="col-6 text-right text-uppercase" id="nama-kasir">-</div>
                            <div class="col-6">WAKTU BUKA:</div>
                            <div class="col-6 text-right" id="waktu-buka">-</div>
                            <div class="col-6">WAKTU TUTUP:</div>
                            <div class="col-6 text-right" id="waktu-tutup">-</div>
                        </div>

                        <hr>

                        <div class="mb-3 row font-weight-bold">
                            <div class="col-6">MODAL AWAL:</div>
                            <div class="col-6 text-right" id="modal-awal">Rp 0</div>
                        </div>

                        <div class="mb-3">
                            <h6 class="font-weight-bold mb-2">Rincian Penjualan:</h6>
                            <div id="list-metode-pembayaran-shift" class="ml-3"></div>
                            <div class="d-flex justify-content-between font-weight-bold mt-2">
                                <span>Total Penjualan:</span>
                                <span id="total-penjualan" class="text-right">Rp 0</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="font-weight-bold mb-2">Rincian Refund:</h6>
                            <div id="list-refund-shift" class="ml-3"></div>
                            <div class="d-flex justify-content-between font-weight-bold text-danger mt-2">
                                <span>Total Refund:</span>
                                <span id="total-refund" class="text-right">- Rp 0</span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-6 font-weight-bold">Total Penerimaan Kasir:</div>
                            <div class="col-6 text-right font-weight-bold" id="total-penerimaan">Rp 0</div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Penerimaan per Rekening:</h6>
                            <div id="list-penerimaan-rekening" class="ml-3"></div>
                        </div>

                        <hr>

                        <div class="mb-3 row">
                            <div class="col-6"><strong class="text-danger">Saldo Akhir:</strong></div>
                            <div class="col-6 text-right"><strong class="text-danger" id="modal-akhir">Rp 0</strong>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3 row small">
                            <div class="col-6">Transaksi Selesai:</div>
                            <div class="col-6 text-right" id="transaksi-selesai">0 transaksi</div>

                            <div class="col-6">Transaksi Belum Terbayar:</div>
                            <div class="col-6 text-right" id="transaksi-pending">0 transaksi</div>

                            <div class="col-6">Nominal Belum Terbayar:</div>
                            <div class="col-6 text-right" id="total-pending">Rp 0</div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light rounded-bottom">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="btn-confirm-tutup-shift">Tutup Shift</button>
                    </div>

                </div>
            </form>
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
        let totalPenjualan = 0;

        <?php if ($show_modal_awal): ?>
        $(document).ready(function() {
            $("#modalAwalKasir").modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        <?php endif; ?>

        // Sembunyikan tombol simpan perubahan saat halaman dimuat
        $("#simpan-perubahan").hide();


        $("#formModalAwal").on('submit', function(e) {
            e.preventDefault();
            var modal_awal = $("#modal_awal").val();
            var keterangan = $("#shift-keterangan").val();

            $.ajax({
                url: base_url + "kasir/start_shift",
                method: "POST",
                data: {
                    modal_awal: modal_awal,
                    keterangan: keterangan
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire('Berhasil', 'Shift dimulai', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal', 'Terjadi kesalahan koneksi.', 'error');
                }
            });
        });




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


        var allowSearchCustomer = true; // flag global

        $("#customer-type").on("change", function() {
            const tipe = $(this).val();
            if (tipe === "member") {
                allowSearchCustomer = true;
                $("#customer-member").show();
                $("#customer-walkin").hide();
                $("#walkin-customer-name").val("");
            } else {
                allowSearchCustomer = false;
                $("#customer-member").hide();
                $("#customer-walkin").show();
                $("#search-customer").val("");
                $("#customer-id").val("");
                $("#customer-list").hide();
            }
        });

        $("#search-customer").on("keyup", function() {
            if (!allowSearchCustomer) return; // Kalau tidak boleh cari, langsung keluar

            let search = $(this).val().trim();
            if (search.length > 0) {
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

        //DI KOMEN UNTUK TES PRODUK PAKET
        // function loadProduk(divisi = "", search = "") {
        //     $.ajax({
        //         url: base_url + "kasir/load_produk",
        //         type: "GET",
        //         dataType: "json",
        //         data: {
        //             divisi,
        //             search
        //         },
        //         success: function(response) {

        //             let produkHtml = "";
        //             $.each(response, function(index, produk) {
        //                 produkHtml += `
        //             <div class="col-md-4">
        //                 <div class="card text-center p-2">
        //                     <img src="${base_url}uploads/produk/${produk.foto}" class="img-fluid">
        //                     <h5>${produk.nama_produk}</h5>
        //                     <p>${formatRupiah(produk.harga_jual)}</p>
        //                     <button class="btn btn-primary add-to-cart"
        //                         data-id="${produk.id}"
        //                         data-nama="${produk.nama_produk}"
        //                         data-harga="${produk.harga_jual}">
        //                         Tambah
        //                     </button>
        //                 </div>
        //             </div>`;
        //             });
        //             $("#produk-list").html(produkHtml);
        //         }
        //     });
        // }

        function loadProduk(divisi = "", search = "") {
            $.ajax({
                url: base_url + "kasir/load_produk",
                type: "GET",
                dataType: "json",
                data: {
                    divisi,
                    search
                },
                success: function(response) {
                    let produkHtml = "";
                    $.each(response, function(index, produk) {
                        // Deteksi apakah produk ini adalah paket
                        const isPaket = produk.pr_divisi_id == 4; // divisi 4 = Paket

                        produkHtml += `
                            <div class="col-md-4">
                                <div class="card text-center p-2">
                                    <img src="${base_url}uploads/produk/${produk.foto}" class="img-fluid">
                                    <h5>${produk.nama_produk}</h5>
                                    <p>${formatRupiah(produk.harga_jual)}</p>
                                    <button class="btn btn-primary add-to-cart"
                                        data-id="${produk.id}"
                                        data-nama="${produk.nama_produk}"
                                        data-harga="${produk.harga_jual}"
                                        ${isPaket ? 'data-is-paket="1" data-paket-id="' + produk.id + '"' : ''}>
                                        Tambah
                                    </button>
                                </div>
                            </div>`;
                    });
                    $("#produk-list").html(produkHtml);
                }

            });
        }

        // Tampilkan tombol X saat ada ketikan
        $('#search').on('input', function() {
            if ($(this).val().length > 0) {
                $('#clear-search').show();
            } else {
                $('#clear-search').hide();
            }
        });

        // Klik tombol X untuk bersihkan
        $('#clear-search').click(function() {
            $('#search').val('');
            $('#clear-search').hide();
            $('#search').trigger('input'); // Untuk trigger event search lagi kalau ada
        });

        $("#search").on("keyup", function() {
            let search = $(this).val().trim();
            let kategori = $("#kategori-tab .active").data("kategori");
            loadProduk(kategori, search);
        });

        $("#divisi-tab button").on("click", function() {
            $("#divisi-tab button").removeClass("active");
            $(this).addClass("active");

            let divisi = $(this).data("divisi");
            let search = $("#search").val().trim();
            loadProduk(divisi, search); // parameter diganti dari kategori ke divisi
        });

        loadProduk();


        // DI KOMEN UNTUK TES PRODUK PAKET
        // $(document).on("click", ".add-to-cart", function() {
        //     const id = $(this).data("id");
        //     const nama = $(this).data("nama");
        //     const harga = parseInt($(this).data("harga"));
        //     const uid = Date.now(); // unique row id for extra reference

        //     // ✅ Siapkan data extra kosong agar tidak error saat trigger input
        //     if (!extraData[uid]) extraData[uid] = [];

        //     const row = $(`
        //     <tr data-id="${id}" data-uid="${uid}">
        //         <td>${nama}</td>
        //         <td>${formatRupiah(harga)}</td>
        //         <td><input type="number" class="form-control qty" value="1" min="1" data-harga="${harga}"></td>
        //         <td class="total"></td> <!-- Kosongkan dulu -->
        //         <td>
        //             <button class="btn btn-sm btn-secondary btn-extra" data-id="${id}" data-uid="${uid}">Tambah Extra</button>
        //         </td>
        //         <td><input type="text" class="form-control catatan" placeholder="Tambahkan catatan (opsional)"></td>
        //         <td><button class="btn btn-danger btn-sm delete-item"><i class="fas fa-trash-alt"></i></button></td>
        //     </tr>`);


        $(document).on("click", ".add-to-cart", function() {
            const id = $(this).data("id");
            const nama = $(this).data("nama");
            const harga = parseInt($(this).data("harga"));
            const isPaket = $(this).data("is-paket") || 0;
            const paketId = $(this).data("paket-id") || null;
            const uid = Date.now();

            if (!extraData[uid]) extraData[uid] = [];

            const row = $(`
        <tr data-id="${id}" data-uid="${uid}" data-is-paket="${isPaket}" data-paket-id="${paketId}">
            <td>${nama}</td>
            <td>${formatRupiah(harga)}</td>
            <td><input type="number" class="form-control qty" value="1" min="1" data-harga="${harga}"></td>
            <td class="total"></td>
            <td>
                <button class="btn btn-sm btn-primary btn-extra"
                    data-id="${id}"
                    data-uid="${uid}"
                    ${isPaket ? 'disabled title="Extra tidak tersedia untuk produk paket"' : ''}>
                    Tambah Extra
                </button>

            </td>
            <td><input type="text" class="form-control catatan" placeholder="Tambahkan catatan (opsional)"></td>
            <td><button class="btn btn-danger btn-sm delete-item"><i class="fas fa-trash-alt"></i></button></td>
        </tr>`);

            const extraRow = $(`
        <tr class="extra-row" data-parent="${uid}">
            <td colspan="7">
                <ul class="list-extra pl-4 mb-0 text-muted small" data-uid="${uid}"></ul>
            </td>
        </tr>`);

            $("#order-list").prepend(extraRow.hide().fadeIn());
            $("#order-list").prepend(row.hide().fadeIn());

            setTimeout(() => {
                row.find(".qty").trigger("input");
            }, 0);
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
                    jumlah: 1 // ✅ SELALU 1
                });
            });

            extraData[currentUID] = selected;

            const jumlahProduk = parseInt($(`[data-uid="${currentUID}"]`).find(".qty").val()) || 1;

            const list = selected.map(e =>
                `<li>
                    ➕ ${jumlahProduk}x ${e.nama}
                    <span class="float-end">Rp ${(e.harga * jumlahProduk).toLocaleString('id-ID')}</span>
                    <button class='btn btn-sm btn-outline-danger btn-hapus-extra ml-1'><i class='fas fa-times'></i></button>
                </li>`
            ).join("");


            $(`.list-extra[data-uid="${currentUID}"]`).html(list);
            updateTotal();
            $("#modalExtra").modal("hide");
        });



        // Fungsi untuk cek apakah keranjang masih ada isinya
        function isOrderListNotEmpty() {
            return $('#order-list').children().length > 0;
        }

        // Event sebelum unload
        window.addEventListener('beforeunload', function(e) {
            if (isOrderListNotEmpty()) {
                var confirmationMessage =
                    'Pesanan Anda belum disimpan. Apakah Anda yakin ingin keluar atau refresh halaman?';
                (e || window.event).returnValue = confirmationMessage; // Gecko + IE
                return confirmationMessage; // Gecko + Webkit, Safari, Chrome
            }
        });
        // Fungsi bantu untuk mengambil data transaksi
        // function getOrderData() {
        //     return {
        //         transaksi_id: $("#transaksi-id").val(),
        //         jenis_order_id: $("#jenis-order").val(),
        //         customer_type: $("#customer-type").val(),
        //         customer_id: $("#customer-id").val(),
        //         customer: $("#search-customer").val() || $("#walkin-customer-name").val(),
        //         nomor_meja: $("#nomor-meja").val(),
        //         // kode_voucher: $("#kode-voucher").val(),
        //         // diskon: $("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, ""),
        //         items: []
        //     };
        // }

        function getOrderData() {
            return {
                transaksi_id: $("#transaksi-id").val(),
                jenis_order_id: $("#jenis-order").val(),
                customer_type: $("#customer-type").val(),
                customer_id: $("#customer-id").val(),
                customer: $("#search-customer").val() || $("#walkin-customer-name").val(),
                nomor_meja: $("#nomor-meja").val(),
                items: []
            };
        }


        $("#simpan-transaksi, #simpan-perubahan").on("click", function() {
            let orderData = getOrderData();
            orderData.transaksi_id = $("#transaksi-id").val();

            $("#order-list tr[data-id]").each(function() {
                const uid = $(this).data("uid");

                let isPaket = $(this).data("is-paket") == 1;
                if (isPaket) {
                    orderData.items.push({
                        pr_produk_id: $(this).data("id"),
                        jumlah: $(this).find(".qty").val(),
                        harga: $(this).find(".qty").data("harga"),
                        catatan: $(this).find(".catatan").val(), // ✅ tambahkan catatan
                        is_paket: 1,
                        pr_produk_paket_id: $(this).data("paket-id"),
                        paket_items: $(this).data("paket-items")
                    });
                } else {
                    orderData.items.push({
                        pr_produk_id: $(this).data("id"),
                        detail_id: $(this).data("detail-id") ?? null,
                        jumlah: $(this).find(".qty").val(),
                        harga: $(this).find(".qty").data("harga"),
                        subtotal: $(this).find(".total").text().replace("Rp ", "")
                            .replace(/\./g, ""),
                        catatan: $(this).find(".catatan").val(),
                        extra: extraData[uid] || [],
                        is_printed: $(this).data("printed") || 0,
                        is_paket: 0
                    });
                }
            });

            // $("#order-list tr[data-id]").each(function() {
            //     const uid = $(this).data("uid");

            //     orderData.items.push({
            //         pr_produk_id: $(this).data("id"),
            //         detail_id: $(this).data("detail-id") ?? null,
            //         jumlah: $(this).find(".qty").val(),
            //         harga: $(this).find(".qty").data("harga"),
            //         subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
            //             /\./g, ""),
            //         catatan: $(this).find(".catatan").val(),
            //         extra: extraData[uid] || [],
            //         is_printed: $(this).data("printed") || 0,
            //         is_paket: $(this).data("is-paket") || 0,
            //         pr_produk_paket_id: $(this).data("paket-id") || null
            //     });
            // });


            //DI KOMEN UNTUK TES PRODUK PAKET

            // $("#order-list tr[data-id]").each(function() {
            //     const uid = $(this).data("uid");

            //     orderData.items.push({
            //         pr_produk_id: $(this).data("id"),
            //         detail_id: $(this).data("detail-id") ?? null,
            //         jumlah: $(this).find(".qty").val(),
            //         harga: $(this).find(".qty").data("harga"),
            //         subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
            //             /\./g, ""),
            //         catatan: $(this).find(".catatan").val(),
            //         extra: extraData[$(this).data("uid")] || [],
            //         is_printed: $(this).data("printed") || 0
            //     });
            // });


            // 🔥 Deteksi tombol yang ditekan
            const isEdit = $(this).attr("id") === "simpan-perubahan";
            const spinnerId = isEdit ? "#spinner-perubahan" : "#spinner-transaksi";
            const textId = isEdit ? "#text-perubahan" : "#text-transaksi";

            // 🔃 Tampilkan loading
            $("#simpan-transaksi, #simpan-perubahan").prop("disabled", true);
            $(spinnerId).removeClass("d-none");
            $(textId).text("Menyimpan...");

            $.ajax({
                url: base_url + "kasir/simpan_transaksi",
                type: "POST",
                dataType: "json",
                data: {
                    order_data: JSON.stringify(orderData)
                },
                success: function(response) {
                    // ✅ Kembalikan tampilan tombol
                    $("#simpan-transaksi, #simpan-perubahan").prop("disabled", false);
                    $(spinnerId).addClass("d-none");
                    $(textId).text(isEdit ? "Simpan Perubahan" : "Simpan Pesanan");

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


        // $("#simpan-transaksi, #simpan-perubahan").on("click", function() {
        //     let orderData = getOrderData();
        //     orderData.transaksi_id = $("#transaksi-id").val();

        //     $("#order-list tr[data-id]").each(function() {
        //         const $row = $(this);
        //         const uid = $row.data("uid");
        //         const isPaket = $row.data("is-paket") == 1;

        //         if (isPaket) {
        //             const paketItems = $row.data("paket-items");
        //             if (!paketItems || paketItems.length == 0) return; // ⛔ skip jika kosong

        //             orderData.items.push({
        //                 is_paket: 1,
        //                 pr_produk_id: $row.data("id"),
        //                 pr_produk_paket_id: $row.data("paket-id"),
        //                 harga: $row.find(".qty").data("harga"),
        //                 jumlah: $row.find(".qty").val(),
        //                 paket_items: paketItems
        //             });
        //         } else {
        //             orderData.items.push({
        //                 is_paket: 0,
        //                 pr_produk_id: $row.data("id"),
        //                 detail_id: $row.data("detail-id") ?? null,
        //                 jumlah: $row.find(".qty").val(),
        //                 harga: $row.find(".qty").data("harga"),
        //                 catatan: $row.find(".catatan").val(),
        //                 extra: extraData[uid] || [],
        //                 is_printed: $row.data("printed") || 0
        //             });
        //         }
        //     });

        //     // $("#order-list tr[data-id]").each(function() {
        //     //     const uid = $(this).data("uid");

        //     //     orderData.items.push({
        //     //         pr_produk_id: $(this).data("id"),
        //     //         detail_id: $(this).data("detail-id") ?? null,
        //     //         jumlah: $(this).find(".qty").val(),
        //     //         harga: $(this).find(".qty").data("harga"),
        //     //         subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
        //     //             /\./g, ""),
        //     //         catatan: $(this).find(".catatan").val(),
        //     //         extra: extraData[uid] || [],
        //     //         is_printed: $(this).data("printed") || 0,
        //     //         is_paket: $(this).data("is-paket") || 0,
        //     //         pr_produk_paket_id: $(this).data("paket-id") || null
        //     //     });
        //     // });


        //     //DI KOMEN UNTUK TES PRODUK PAKET

        //     // $("#order-list tr[data-id]").each(function() {
        //     //     const uid = $(this).data("uid");

        //     //     orderData.items.push({
        //     //         pr_produk_id: $(this).data("id"),
        //     //         detail_id: $(this).data("detail-id") ?? null,
        //     //         jumlah: $(this).find(".qty").val(),
        //     //         harga: $(this).find(".qty").data("harga"),
        //     //         subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
        //     //             /\./g, ""),
        //     //         catatan: $(this).find(".catatan").val(),
        //     //         extra: extraData[$(this).data("uid")] || [],
        //     //         is_printed: $(this).data("printed") || 0
        //     //     });
        //     // });


        //     // 🔥 Deteksi tombol yang ditekan
        //     const isEdit = $(this).attr("id") === "simpan-perubahan";
        //     const spinnerId = isEdit ? "#spinner-perubahan" : "#spinner-transaksi";
        //     const textId = isEdit ? "#text-perubahan" : "#text-transaksi";

        //     // 🔃 Tampilkan loading
        //     $("#simpan-transaksi, #simpan-perubahan").prop("disabled", true);
        //     $(spinnerId).removeClass("d-none");
        //     $(textId).text("Menyimpan...");

        //     $.ajax({
        //         url: base_url + "kasir/simpan_transaksi",
        //         type: "POST",
        //         dataType: "json",
        //         data: {
        //             order_data: JSON.stringify(orderData)
        //         },
        //         success: function(response) {
        //             // ✅ Kembalikan tampilan tombol
        //             $("#simpan-transaksi, #simpan-perubahan").prop("disabled", false);
        //             $(spinnerId).addClass("d-none");
        //             $(textId).text(isEdit ? "Simpan Perubahan" : "Simpan Pesanan");

        //             alert((response.status === "success" ? "✅" : "❌") + " " + response
        //                 .message);
        //             if (response.status === "success") {
        //                 kosongkanKeranjang();
        //                 resetFormToBaru();
        //                 loadPendingOrders();
        //             }
        //         }
        //     });
        // });



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
            // Tambahkan ini untuk refresh halaman
            // location.reload();
        }


        $("#kosongkan-keranjang").click(function() {
            if (confirm("Yakin ingin mengosongkan keranjang?")) {
                kosongkanKeranjang();
                location.reload();

            }
        });


        function loadPendingOrders(keyword = "") {
            $.get(base_url + "kasir/load_pending_orders", {
                search: keyword
            }, function(data) {
                let orders = JSON.parse(data);
                let html = "";
                if (orders.length === 0) {
                    html = "<p class='text-muted text-center mt-2'>Tidak ada pesanan</p>";
                } else {
                    orders.forEach(order => {
                        html += `
                <div class="pesanan-item" data-id="${order.id}">
                    <div><strong>${order.no_transaksi}</strong></div>
                    <div>${order.customer} - Meja ${order.nomor_meja}</div>
                    <div>Rp ${parseInt(order.total_penjualan).toLocaleString("id-ID")}</div>
                </div>`;
                    });
                }
                $("#pending-orders").html(html);
            });
        }

        $(document).on('input', '#searchPendingOrder', function() {
            const keyword = $(this).val();
            loadPendingOrders(keyword);
        });


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

                    // 🔥 Tambahkan filter hanya item aktif (status null atau kosong)
                    const activeItems = res.items.filter(item => !item.status || item
                        .status === '');


                    activeItems.forEach((item) => {
                        if (item.pr_detail_transaksi_paket_id)
                            return; // ❗ lewati isi paket

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

                    const paketMap = {};

                    // 1. Kelompokkan produk paket berdasarkan detail_unit_paket_id
                    activeItems.forEach((item) => {
                        const isPaketItem = item.pr_detail_transaksi_paket_id;
                        const isAktif = !item.status || item.status === '';

                        if (isPaketItem && isAktif) {
                            const unitKey = item.detail_unit_paket_id;

                            if (!paketMap[unitKey]) {
                                // Ambil data dari produk utama (harga > 0)
                                const isMaster = parseInt(item.harga) > 0;

                                paketMap[unitKey] = {
                                    nama_paket: item.nama_paket,
                                    pr_produk_id: item.pr_produk_paket_id,
                                    // harga: isMaster ? item.harga :
                                    // 0, // hanya dari produk utama
                                    harga: item.harga_paket,
                                    jumlah: item.jumlah_paket,
                                    // jumlah: isMaster ? item.jumlah :
                                    // 0, // hanya dihitung 1x dari produk utama
                                    paket_id: item.pr_detail_transaksi_paket_id,
                                    paket_items: {}
                                };
                            }

                            const pid = item.pr_produk_id;
                            if (!paketMap[unitKey].paket_items[pid]) {
                                paketMap[unitKey].paket_items[pid] = {
                                    pr_produk_id: pid,
                                    nama_produk: item.nama_produk,
                                    jumlah: 0,
                                    extra: item.extra || []
                                };
                            }

                            // Jumlah isi produk selalu dijumlahkan
                            paketMap[unitKey].paket_items[pid].jumlah += parseInt(
                                item.jumlah);
                        }
                    });



                    // 🔁 Render produk paket terlebih dahulu
                    Object.entries(paketMap).forEach(([unitId, paket], i) => {
                        const uid = Date.now() + "_p_" + i;
                        extraData[uid] = []; // Paket tidak bisa di-extra manual

                        const items_html = Object.values(paket.paket_items).map(
                            prod =>
                            `<li class="text-muted small">↳ ${prod.nama_produk} (${prod.jumlah})</li>`
                        ).join('');

                        let row = `
        <tr data-id="${paket.pr_produk_id}" data-uid="${uid}" data-is-paket="1" 
            data-paket-id="${paket.paket_id}" data-paket-items='${JSON.stringify(Object.values(paket.paket_items))}'>
            <td>${paket.nama_paket}</td>
            <td>${formatRupiah(paket.harga)}</td>
            <td>
                <input type="number" class="form-control qty" value="${paket.jumlah}" 
                    data-harga="${paket.harga}" readonly>
            </td>
            <td class="total">${formatRupiah(paket.jumlah * paket.harga)}</td>
            <td><button class="btn btn-secondary btn-sm" disabled>Extra Nonaktif</button></td>
            <td><input type="text" class="form-control" readonly value="Paket"></td>
            <td></td>
        </tr>
        <tr class="extra-row" data-parent="${uid}">
            <td colspan="7">
                <ul class="list-extra pl-4 mb-0 text-muted small">
                    ${items_html}
                </ul>
            </td>
        </tr>
    `;
                        $("#order-list").append(row);
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
        //         $("#rincian-pesanan").click(function() {
        //             const selected = $(".pesanan-item.selected");
        //             if (!selected.length) {
        //                 alert("Pilih salah satu pesanan!");
        //                 return;
        //             }

        //             const transaksi_id = selected.data("id");

        //             $.post(base_url + "kasir/get_detail_transaksi_aktif", {
        //                 transaksi_id
        //             }, function(res) {
        //                 console.log(res); // ⬅️ Tambahkan ini                
        //                 // // Info transaksi
        //                 $("#rinci-no-transaksi").text(res.no_transaksi);
        //                 $("#rinci-customer").text(res.customer || "-");
        //                 $("#rinci-jenis-order").text(res.jenis_order || "-");
        //                 $("#rinci-meja").text(res.nomor_meja || "-");
        //                 $("#rinci-voucher").text(res.kode_voucher || "-");
        //                 $("#rinci-diskon").text("Rp " + (res.diskon || 0).toLocaleString("id-ID"));

        //                 totalPenjualanAwal = parseInt(res.total_penjualan || res.total_pembayaran || 0);
        //                 $("#rinci-total").text(totalPenjualanAwal.toLocaleString("id-ID"));

        //                 // ⬇️ Tambahan ini:
        //                 $("#rinci-tagihan").text(totalPenjualanAwal.toLocaleString("id-ID"));

        //                 // ⬅️ Tambahkan ini di SINI
        //                 rinciOrderItems = res.items;


        //                 let itemHtml = '';
        //                 const grouped = {};

        //                 res.items.forEach(item => {
        //                     const groupKey = item.pr_detail_transaksi_paket_id || item
        //                         .detail_unit_id || item.id;

        //                     if (!grouped[groupKey]) {
        //                         grouped[groupKey] = {
        //                             nama_produk: item.nama_paket || item.nama_produk,
        //                             harga: item.nama_paket ? parseInt(item.harga_paket) :
        //                                 parseInt(item.harga),
        //                             jumlah: 0,
        //                             extra: {},
        //                             is_paket: !!item.nama_paket,
        //                             items: []
        //                         };
        //                     }

        //                     if (item.nama_paket) {
        //                         // ✅ Hanya set jumlah sekali (ambil dari paket)
        //                         if (grouped[groupKey].jumlah === 0) {
        //                             grouped[groupKey].jumlah = parseInt(item.jumlah);
        //                         }

        //                         // ✅ Tambah produk isi paket
        //                         grouped[groupKey].items.push({
        //                             nama_produk: item.nama_produk,
        //                             jumlah: item.jumlah
        //                         });
        //                     } else {
        //                         grouped[groupKey].jumlah += parseInt(item.jumlah);
        //                     }

        //                     // ✅ Handle extra
        //                     if (item.extra?.length) {
        //                         item.extra.forEach(extra => {
        //                             const key = extra.nama;
        //                             if (!grouped[groupKey].extra[key]) {
        //                                 grouped[groupKey].extra[key] = {
        //                                     nama: extra.nama,
        //                                     harga: parseInt(extra.harga),
        //                                     jumlah: 0
        //                                 };
        //                             }
        //                             grouped[groupKey].extra[key].jumlah += parseInt(
        //                                 extra.jumlah);
        //                         });
        //                     }
        //                 });


        //                 // 🔁 RENDER KE TABEL
        //                 Object.values(grouped).forEach(item => {
        //                     itemHtml += `
        // <tr>
        //     <td>${item.nama_produk}</td>
        //     <td class="text-center">Rp ${item.harga.toLocaleString('id-ID')}</td>
        //     <td class="text-center">${item.jumlah}</td>
        //     <td class="text-right">Rp ${(item.harga * item.jumlah).toLocaleString('id-ID')}</td>
        // </tr>`;

        //                     // Detail isi paket
        //                     if (item.is_paket && item.items) {
        //                         item.items.forEach(sub => {
        //                             itemHtml += `
        // <tr class="text-muted small">
        //     <td class="pl-4">↳ ${sub.nama_produk}</td>
        //     <td class="text-center">Rp 0</td>
        //     <td class="text-center">${sub.jumlah}</td>
        //     <td class="text-right">Rp 0</td>
        // </tr>`;
        //                         });
        //                     }

        //                     // Extra
        //                     if (item.extra) {
        //                         Object.values(item.extra).forEach(extra => {
        //                             itemHtml += `
        // <tr class="text-muted small">
        //     <td class="pl-4">➕ ${extra.nama}</td>
        //     <td class="text-center">Rp ${extra.harga.toLocaleString('id-ID')}</td>
        //     <td class="text-center">${extra.jumlah}</td>
        //     <td class="text-right">Rp ${(extra.harga * extra.jumlah).toLocaleString('id-ID')}</td>
        // </tr>`;
        //                         });
        //                     }
        //                 });

        //                 $("#rinci-item-list").html(itemHtml);


        //                 // pastikan total bayar tampil benar juga
        //                 $("#rinci-total").text(parseInt(res.total_pembayaran || res.total_penjualan ||
        //                     0).toLocaleString("id-ID"));

        //                 $("#modalRincianPesanan").modal("show");

        //                 // simpan transaksi_id ke tombol Bayar
        //                 $("#btn-buka-bayar").data("id", transaksi_id);
        //             }, "json");
        //         });


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
                // Info utama
                $("#rinci-no-transaksi").text(res.no_transaksi);
                $("#rinci-customer").text(res.customer || "-");
                $("#rinci-jenis-order").text(res.jenis_order || "-");
                $("#rinci-meja").text(res.nomor_meja || "-");
                $("#rinci-voucher").text(res.kode_voucher || "-");
                $("#rinci-diskon").text("Rp " + (res.diskon || 0).toLocaleString("id-ID"));

                totalPenjualanAwal = parseInt(res.total_penjualan || res.total_pembayaran || 0);
                $("#rinci-tagihan").text(totalPenjualanAwal.toLocaleString("id-ID"));

                rinciOrderItems = res.items;

                let itemHtml = '';
                const grouped = {};

                res.items.forEach(item => {
                    const groupKey = item.is_paket ? item.detail_unit_paket_id : (item
                        .detail_unit_id || item.id);

                    if (!grouped[groupKey]) {
                        grouped[groupKey] = {
                            nama_produk: item.nama_produk,
                            harga: item.harga,
                            jumlah: 0,
                            is_paket: item.is_paket == 1,
                            items: item.produk_dalam ?? [],
                            extra: {}
                        };
                    }

                    if (item.is_paket == 1) {
                        grouped[groupKey].jumlah = item
                            .jumlah; // ✅ gunakan jumlah dari paket
                    } else {
                        grouped[groupKey].jumlah += parseInt(item.jumlah);
                    }


                    // Tambahkan extra
                    if (item.extra?.length) {
                        item.extra.forEach(extra => {
                            const k = extra.nama;
                            if (!grouped[groupKey].extra[k]) {
                                grouped[groupKey].extra[k] = {
                                    nama: extra.nama,
                                    harga: parseInt(extra.harga),
                                    jumlah: 0
                                };
                            }
                            grouped[groupKey].extra[k].jumlah += parseInt(extra
                                .jumlah);
                        });
                    }
                });

                // Render ke tabel
                Object.values(grouped).forEach(item => {
                    itemHtml += `
                                <tr>
                                    <td>${item.nama_produk}</td>
                                    <td class="text-center">Rp ${item.harga.toLocaleString('id-ID')}</td>
                                    <td class="text-center">${item.jumlah}</td>
                                    <td class="text-right">Rp ${(item.harga * item.jumlah).toLocaleString('id-ID')}</td>
                                </tr>`;

                    if (item.is_paket && item.items.length > 0) {
                        item.items.forEach(sub => {
                            itemHtml += `
<tr class="text-muted small">
    <td class="pl-4">↳ ${sub.nama_produk}</td>
    <td class="text-center">Rp 0</td>
    <td class="text-center">${sub.jumlah}</td>
    <td class="text-right">Rp 0</td>
</tr>`;
                        });
                    }


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
                $("#rinci-total").text(parseInt(res.total_pembayaran || res.total_penjualan ||
                    0).toLocaleString("id-ID"));
                $("#modalRincianPesanan").modal("show");
                $("#btn-buka-bayar").data("id", transaksi_id);
            }, "json");
        });


        // Fungsi cari voucher

        $("#btn-open-voucher").click(function() {
            $("#modalPilihVoucher").modal("show");
            loadVoucherGallery(); // Load saat dibuka
        });

        $('#modalVoucherList').on('shown.bs.modal', function() {
            $('#searchVoucher').val('');
            loadVoucherList(); // ⬅️ langsung tampilkan
        });


        function loadVoucherList(keyword = "") {
            $.ajax({
                url: base_url + "kasir/search_voucher",
                method: "GET",
                data: {
                    keyword: keyword
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    let html = "";

                    if (data.length === 0) {
                        html = "<p class='text-muted'>Voucher tidak ditemukan.</p>";
                    } else {
                        data.forEach(voucher => {
                            // ✅ Format diskon
                            let diskon_text = "-";
                            if (voucher.jenis === "nominal") {
                                diskon_text = "Rp " + parseInt(voucher.nilai)
                                    .toLocaleString("id-ID");
                            } else if (voucher.jenis === "persentase") {
                                diskon_text = voucher.nilai + "%";
                            }

                            html += `
                        <div class="voucher-card">
                            <img src="${base_url}uploads/logo_1743996208.png" class="voucher-logo">
                            <div class="voucher-info">
                                <h5>${voucher.kode_voucher}</h5>
                                <p>Diskon: ${diskon_text} | Min: Rp ${parseInt(voucher.min_pembelian).toLocaleString("id-ID")}</p>
                                <p class="text-muted">Berlaku sampai: ${voucher.tanggal_berakhir}</p>
                            </div>
                            <button class="btn-use-voucher use-voucher" data-kode="${voucher.kode_voucher}">Gunakan</button>
                        </div>
                    `;
                        });
                    }

                    $("#voucher-list-container").html(html);
                }
            });
        }


        // Search real-time
        $("#searchVoucher").on("keyup", function() {
            loadVoucherList($(this).val());
        });

        // Ketika klik "Gunakan"
        $(document).on("click", ".use-voucher", function() {
            const kode = $(this).data("kode");
            $("#input-voucher").val(kode);
            $("#modalVoucherList").modal("hide");
        });

        // fungsi cek voucher
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

            // 🔄 Tampilkan spinner dan disable tombol
            $("#btn-submit-bayar").prop("disabled", true);
            $("#spinner-bayar").removeClass("d-none");
            $("#text-bayar").text("Memproses...");

            const totalBayar = pembayaranList.reduce((sum, p) => sum + (parseInt(p.jumlah) || 0), 0);
            const diskon = parseInt($("#multi-diskon").text().replace(/\D/g, "")) || 0;
            const kode_voucher = $("#input-voucher").val().trim() || "";

            $.post(base_url + "kasir/simpan_pembayaran", {
                transaksi_id: $("#bayar-transaksi-id").val(),
                pembayaran: JSON.stringify(pembayaranList),
                kode_voucher: kode_voucher,
                diskon: diskon
            }, function(res) {
                $("#btn-submit-bayar").prop("disabled", false);
                $("#spinner-bayar").addClass("d-none");
                $("#text-bayar").text("Selesaikan Pembayaran");

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

                    // res.items.forEach(function(item) {
                    //     const isBatal = item.status === 'batal';
                    //     const disabled = isBatal ? 'disabled checked' : '';
                    //     const classMuted = isBatal ? 'batal' : 'produk';

                    //     html += `
                    //         <label class="list-group-item d-flex justify-content-between align-items-center ${classMuted}">
                    //             <div class="d-flex align-items-center">
                    //                 <input class="form-check-input me-2 checkbox-void" type="checkbox" ${disabled} value="${item.id}" data-type="produk" id="produk-${item.id}">
                    //                 <span>${item.jumlah}x ${item.nama_produk}</span>
                    //             </div>
                    //             <span class="badge badge-produk rounded-pill">${formatRupiah(item.harga * item.jumlah)}</span>
                    //         </label>
                    //      `;

                    //     if (item.extra && item.extra.length > 0) {
                    //         item.extra.forEach(function(extra) {
                    //             html += `
                    //         <label class="list-group-item d-flex justify-content-between align-items-center extra">
                    //             <div class="d-flex align-items-center">
                    //                 <input class="form-check-input me-2 checkbox-void" type="checkbox" value="${extra.id}" data-type="extra" data-parent-id="${item.id}" id="extra-${extra.id}">
                    //                 <small>➔ ${extra.nama}</small>
                    //             </div>
                    //             <span class="badge badge-extra rounded-pill">${formatRupiah(extra.harga * extra.jumlah)}</span>
                    //         </label>
                    //              `;
                    //         });
                    //     }
                    // });


                    res.items.forEach(function(item) {
                        const isBatal = item.status === 'batal';
                        const disabled = isBatal ? 'disabled checked' : '';
                        const classMuted = isBatal ? 'batal' : item.type;

                        html += `
                            <label class="list-group-item d-flex justify-content-between align-items-center ${classMuted}">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2 checkbox-void" type="checkbox" ${disabled} value="${item.id}" data-type="${item.type}" id="${item.type}-${item.id}">
                                    <span class="${item.type === 'paket' ? 'fw-bold text-dark' : ''}">${item.jumlah}x ${item.nama_produk}</span>
                                </div>
                                <span class="badge rounded-pill">${formatRupiah(item.harga * item.jumlah)}</span>
                            </label>
                        `;

                        // Extra
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

                        // Isi paket
                        if (item.type === 'paket' && item.produk_dalam) {
                            item.produk_dalam.forEach(function(isi, index) {
                                html += `
                                <label class="list-group-item d-flex justify-content-between align-items-center isi-paket">
                                    <div class="d-flex align-items-center ms-4">
                                        <input class="form-check-input me-2 checkbox-void child-of-${item.type}-${item.id}" type="checkbox" disabled>
                                        <small>${isi.jumlah}x ${isi.nama_produk}</small>
                                    </div>
                                    <span class="badge rounded-pill">Rp 0</span>
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
        // $(document).on('change', '.checkbox-void', function() {
        //     const id = $(this).val();
        //     const isChecked = $(this).is(':checked');
        //     const type = $(this).data('type');

        //     // Kalau produk utama dicentang, extra ikut
        //     if (type === 'produk') {
        //         $(`input[data-parent-id='${id}']`).prop('checked', isChecked);
        //     }
        // });



        $(document).on('change', '.checkbox-void', function() {
            const id = $(this).val();
            const isChecked = $(this).is(':checked');
            const type = $(this).data('type');

            // Produk → extra ikut
            if (type === 'produk') {
                $(`input[data-parent-id='${id}']`).prop('checked', isChecked);
            }

            // Paket → centang isi-nya juga (pakai class child-of-)
            if (type === 'paket') {
                $(`.child-of-${type}-${id}`).prop('checked', isChecked);
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

            // ⏳ Tampilkan loading spinner
            Swal.fire({
                title: 'Memproses...',
                text: 'Menyimpan data void, mohon tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post(base_url + "kasir/void_pilihan", {
                transaksi_id: transaksi_id,
                items: JSON.stringify(selected),
                alasan: alasan
            }, function(res) {
                Swal.close(); // ✅ Tutup spinner

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
            // ⏳ Spinner untuk proses cetak
            Swal.fire({
                title: 'Mencetak...',
                text: 'Mengirim struk void ke printer...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post(base_url + "kasir/cetak_void_internal", {
                void_ids: void_ids
            }, function(res) {
                Swal.close(); // ✅ Tutup spinner

                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success');
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }, "json");
        }


        // TUTUP SHIFT

        $("#btnTutupShift").click(function() {
            $.ajax({
                url: base_url + "kasir/cek_shift",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    // console.log(response); // <<<<<< DEBUG
                    if (response.status === 'success') {
                        // Data umum
                        $("#nama-kasir").text(response.nama_kasir || '-');
                        $("#waktu-buka").text(formatTanggalWaktu(response.waktu_buka) ||
                            '-');
                        $("#waktu-tutup").text(formatTanggalWaktu(response.waktu_tutup) ||
                            '-');

                        // Data uang
                        $("#modal-awal").text(formatRupiah(response.modal_awal || 0));
                        $("#modal-akhir").text(formatRupiah(response.modal_akhir || 0));
                        // $("#total-penerimaan").text(formatRupiah(response
                        //     .total_penerimaan || 0));
                        // $("#total-penjualan").text(formatRupiah(response.total_penjualan ||
                        //     0));
                        $("#total-pending").text(formatRupiah(response.total_pending || 0));

                        // Data transaksi
                        $("#transaksi-selesai").text(response.transaksi_selesai +
                            " transaksi");
                        $("#transaksi-pending").text(response.transaksi_pending +
                            " transaksi");
                        $("#nominal-belum-terbayar").text("Rp " + formatRupiah(response
                            .total_pending || 0));

                        // Rincian metode pembayaran (INI YANG PENTING)
                        $("#list-metode-pembayaran-shift").empty();
                        if (response.metode_pembayaran && response.metode_pembayaran
                            .length > 0) {
                            response.metode_pembayaran.forEach(function(item) {
                                totalPenjualan += parseFloat(item.total);
                                $("#list-metode-pembayaran-shift").append(`
                                    <div class="d-flex justify-content-between border-bottom py-1">
                                        <span>${item.metode_pembayaran}</span>
                                        <span>${formatRupiah(item.total)}</span>
                                    </div>
                                `);
                            });
                        } else {
                            $("#list-metode-pembayaran-shift").append(
                                `<div class="text-muted">Tidak ada rincian pembayaran.</div>`
                            );
                        }
                        $("#total-penjualan").text(formatRupiah(totalPenjualan));

                        // Rincian Refund
                        $("#list-refund-shift").empty();
                        if (response.refund_per_metode && response.refund_per_metode
                            .length > 0) {
                            response.refund_per_metode.forEach(function(item) {
                                $("#list-refund-shift").append(`
                                    <div class="d-flex justify-content-between border-bottom py-1 text-danger">
                                        <span>${item.metode_pembayaran}</span>
                                        <span>- ${formatRupiah(item.total)}</span>
                                    </div>
                                `);
                            });
                        } else {
                            $("#list-refund-shift").append(
                                `<div class="text-muted">Tidak ada refund.</div>`);
                        }
                        // Total Refund
                        let totalRefund = 0;
                        $("#list-refund-shift").empty();
                        if (response.refund_per_metode && response.refund_per_metode
                            .length > 0) {
                            response.refund_per_metode.forEach(function(item) {
                                totalRefund += parseFloat(item.total);
                                $("#list-refund-shift").append(`
                                    <div class="d-flex justify-content-between border-bottom py-1 text-danger">
                                        <span>${item.metode_pembayaran}</span>
                                        <span>- ${formatRupiah(item.total)}</span>
                                    </div>
                                `);
                            });
                        } else {
                            $("#list-refund-shift").append(
                                `<div class="text-muted">Tidak ada refund.</div>`);
                        }
                        $("#total-refund").text("- " + formatRupiah(totalRefund));

                        // Rincian penerimaan per rekening
                        $("#list-penerimaan-rekening").empty();
                        if (response.penerimaan_per_rekening && response
                            .penerimaan_per_rekening.length > 0) {
                            response.penerimaan_per_rekening.forEach(function(item) {
                                $("#list-penerimaan-rekening").append(`
                                    <div class="d-flex justify-content-between border-bottom py-1 text-primary">
                                        <span>${item.nama_rekening}</span>
                                        <span>${formatRupiah(item.total)}</span>
                                    </div>
                                `);
                            });
                        }

                        // Total Penerimaan = Penjualan - Refund
                        const totalPenerimaan = totalPenjualan - totalRefund;
                        $("#total-penerimaan").text(formatRupiah(totalPenerimaan));

                        // Saldo Akhir = Modal + Total Penerimaan
                        const modalAwal = parseFloat(response.modal_awal || 0);
                        const saldoAkhir = modalAwal + totalPenerimaan;
                        $("#modal-akhir").text(formatRupiah(saldoAkhir));

                        // ✅ Show modal
                        $("#modalTutupShift").modal('show');

                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal', 'Tidak dapat menghubungi server.', 'error');
                }
            });
        });


        // Fungsi format Rupiah
        function formatRupiah(angka) {
            return "Rp " + parseInt(angka).toLocaleString("id-ID");
        }

        // Fungsi format tanggal dan jam
        function formatTanggalWaktu(datetime) {
            if (!datetime) return '-';
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            return new Date(datetime).toLocaleString('id-ID', options);
        }

        // Tombol konfirmasi tutup shift
        $("#btn-confirm-tutup-shift").click(function() {
            Swal.fire({
                title: "Tutup Shift?",
                text: "Pastikan semua transaksi sudah selesai.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Tutup Shift",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + "kasir/tutup_shift",
                        type: "POST",
                        dataType: "json",
                        success: function(res) {
                            if (res.status === 'success') {
                                // ✅ Tutup modal
                                $("#modalTutupShift").modal('hide');

                                // ✅ Cetak laporan langsung
                                window.open(base_url +
                                    "kasir/cetak_laporan_shift/" + res.shift_id,
                                    "_blank");

                                // ✅ Refresh halaman
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                            }
                        }
                    });
                }
            });
        });


        $("#btn-cetak-laporan-shift").click(function() {
            if (shift_id_terakhir) {
                window.open(base_url + "kasir/cetak_laporan_shift/" + shift_id_terakhir, "_blank");
            } else {
                Swal.fire("Shift belum ditutup", "Tidak ada data shift terakhir untuk dicetak.",
                    "warning");
            }
        });



        // SYNC DATA VPS KE LOKAL //
        $("#btn-sync-data-umum").click(function() {
            Swal.fire({
                title: 'Sinkronisasi Data Umum?',
                text: 'Ini akan mengambil data produk, extra, metode pembayaran, dan lainnya dari VPS.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sinkronkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang sinkronisasi...',
                        html: 'Mohon tunggu beberapa saat',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.get("<?= base_url('sync_data/ambil_semua') ?>", function(res) {
                        Swal.close(); // tutup loading

                        const response = JSON.parse(res);
                        let html = "<ul>";
                        for (const [table, status] of Object.entries(response.result)) {
                            html += `<li><b>${table}</b>: ${status}</li>`;
                        }
                        html += "</ul>";

                        Swal.fire("Selesai", html, "success");
                    }).fail(function() {
                        Swal.close();
                        Swal.fire("Gagal", "Tidak bisa menghubungi VPS.", "error");
                    });
                }
            });
        });



    });
    </script>

    <script>
    const shift_id_terakhir = <?= json_encode($shift_id_terakhir ?? null) ?>;
    </script>


</body>

</html>

</body>

</html>