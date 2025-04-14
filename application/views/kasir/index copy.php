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
                <button id="ubah-pesanan" class="btn btn-primary" disabled>Ubah Pesanan</button>
                <button id="rincian-pesanan" class="btn btn-secondary">Rincian Pesanan</button>
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
                    <div class="form-group">
                        <label>Kode Voucher</label>
                        <div class="input-group">
                            <input type="text" id="kode-voucher" class="form-control"
                                placeholder="Masukkan kode voucher">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="cek-voucher">Gunakan</button>
                            </div>
                        </div>
                        <p id="voucher-message" class="text-success"></p>
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

                    <!-- Total Harga Sebelum Diskon -->
                    <h4>Total Harga: <span id="total-harga">Rp 0</span></h4>

                    <!-- Nominal Diskon -->
                    <h4>Diskon: <span id="nominal-diskon">Rp 0</span></h4>

                    <!-- Total Bayar Setelah Diskon -->
                    <h3>Total Bayar: <span id="total-bayar">Rp 0</span></h3>
                    <!-- <h4>Total Bayar: <span id="total-bayar">Rp 0</span></h4> -->
                    <button id="btn-batal" class="btn btn-danger">Batal</button>
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
        function formatRupiah(angka) {
            return "Rp " + parseInt(angka).toLocaleString('id-ID');
        }

        function updateTotal() {
            let totalHarga = 0;
            $(".total").each(function() {
                totalHarga += parseInt($(this).text().replace("Rp ", "").replace(/\./g, "")) || 0;
            });

            let diskon = parseInt($("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, "")) || 0;
            let totalBayar = totalHarga - diskon;

            $("#total-harga").text(formatRupiah(totalHarga)); // Menampilkan total harga sebelum diskon
            $("#total-bayar").text(formatRupiah(totalBayar > 0 ? totalBayar : 0));
        }

        $("#customer-type").on("change", function() {
            if ($(this).val() === "member") {
                $("#customer-member").show();
                $("#customer-walkin").hide();
            } else {
                $("#customer-member").hide();
                $("#customer-walkin").show();
            }
        });

        $("#search-customer").on("keyup", function() {
            let search = $(this).val().trim();
            if (search.length > 1) {
                $.ajax({
                    url: "<?= site_url('kasir/search_customer'); ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        search: search
                    },
                    success: function(response) {
                        let customerHtml = "";
                        $.each(response, function(index, customer) {
                            customerHtml += `<li class="list-group-item select-customer" data-id="${customer.id}" data-nama="${customer.nama}">
                            ${customer.nama} - ${customer.telepon}
                        </li>`;
                        });
                        $("#customer-list").html(customerHtml).show();
                    }
                });
            } else {
                $("#customer-list").hide();
            }
        });

        // ✅ Simpan customer_id ke input hidden saat customer dipilih
        $(document).on("click", ".select-customer", function() {
            let nama = $(this).data("nama");
            let id = $(this).data("id");

            $("#search-customer").val(nama);
            $("#customer-id").val(id); // Simpan ID customer
            $("#customer-list").hide();
        });


        function loadProduk(kategori = "", search = "") {
            $.ajax({
                url: "<?= site_url('kasir/load_produk'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    kategori: kategori,
                    search: search
                },
                success: function(response) {
                    let produkHtml = "";
                    $.each(response, function(index, produk) {
                        produkHtml += `
                        <div class="col-md-4">
                            <div class="card text-center p-2">
                                <img src="<?= base_url('uploads/produk/'); ?>${produk.foto}" class="img-fluid">
                                <h5>${produk.nama_produk}</h5>
                                <p>${formatRupiah(produk.harga_jual)}</p>
                                <button class="btn btn-primary add-to-cart"
                                    data-id="${produk.id}"
                                    data-nama="${produk.nama_produk}"
                                    data-harga="${produk.harga_jual}">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    `;
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
            var id = $(this).data("id");
            var nama = $(this).data("nama");
            var harga = parseInt($(this).data("harga"));

            var row = `<tr data-id="${id}">
            <td>${nama}</td>
            <td>${formatRupiah(harga)}</td>
            <td><input type="number" class="form-control qty" value="1" min="1" data-harga="${harga}"></td>
            <td class="total">${formatRupiah(harga)}</td>
            <td>
                <button class="btn btn-sm btn-secondary btn-extra" data-id="${id}">Tambah Extra</button>
                <ul class="list-extra" data-produk-id="${id}"></ul>
            </td>
            <td><input type="text" class="form-control catatan" placeholder="Tambahkan catatan (opsional)"></td>
            <td><button class="btn btn-danger btn-sm delete-item">
                <i class="fas fa-trash-alt"></i>
            </button></td>
        </tr>`;

            $("#order-list").append(row);
            updateTotal();
        });

        $(document).on("input", ".qty", function() {
            var qty = parseInt($(this).val());
            var harga = parseInt($(this).data("harga"));
            var total = qty * harga;
            $(this).closest("tr").find(".total").text(formatRupiah(total));
            updateTotal();
        });

        $(document).on("click", ".delete-item", function() {
            $(this).closest("tr").remove();
            updateTotal();
        });

        let voucherDigunakan = false;

        $("#cek-voucher").on("click", function() {
            let kode = $("#kode-voucher").val().trim();
            let totalBelanja = parseInt($("#total-harga").text().replace("Rp ", "").replace(/\./g, ""));

            let items = [];
            $("#order-list tr").each(function() {
                items.push({
                    pr_produk_id: $(this).data("id"),
                    subtotal: parseInt($(this).find(".total").text().replace("Rp ", "")
                        .replace(/\./g, ""))
                });
            });

            if (kode === "") {
                $("#voucher-message").text("Kode voucher tidak boleh kosong!").removeClass(
                    "text-success").addClass("text-danger");
                return;
            }

            $.ajax({
                url: "<?= site_url('kasir/cek_voucher'); ?>",
                type: "POST",
                data: {
                    kode_voucher: kode,
                    total: totalBelanja,
                    items: JSON.stringify(items) // Kirim daftar produk dalam transaksi
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        $("#voucher-message").text(
                                "Voucher berhasil digunakan: Diskon Rp " + response.diskon
                                .toLocaleString('id-ID')).removeClass("text-danger")
                            .addClass("text-success");
                        $("#nominal-diskon").text("Rp " + response.diskon.toLocaleString(
                            'id-ID'));
                        updateTotal();
                    } else {
                        $("#voucher-message").text(response.message).removeClass(
                            "text-success").addClass("text-danger");
                        $("#nominal-diskon").text("Rp 0");
                        updateTotal();
                    }
                }
            });
        });



        $("#kode-voucher").on("input", function() {
            $("#voucher-message").text("");
            $("#nominal-diskon").text("Rp 0");
            voucherDigunakan = false;
            updateTotal();
        });


        $("#simpan-transaksi").on("click", function() {
            let orderData = {
                jenis_order_id: $("#jenis-order").val(),
                customer_type: $("#customer-type").val(),
                customer_id: $("#customer-id").val(),
                customer: $("#search-customer").val() || $("#walkin-customer-name").val(),
                nomor_meja: $("#nomor-meja").val(),
                total_penjualan: $("#total-harga").text().replace("Rp ", "").replace(/\./g, ""),
                kode_voucher: $("#kode-voucher").val(),
                diskon: $("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, ""),
                items: []
            };

            $("#order-list tr").each(function() {
                orderData.items.push({
                    pr_produk_id: $(this).data("id"),
                    jumlah: $(this).find(".qty").val(),
                    harga: $(this).find(".qty").data("harga"),
                    subtotal: $(this).find(".total").text().replace("Rp ", "").replace(
                        /\./g, ""),
                    catatan: $(this).find(".catatan").val(),
                    extra: extraData[$(this).data("id")] || []
                });

            });

            $.ajax({
                url: "<?= site_url('kasir/simpan_transaksi'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    order_data: JSON.stringify(orderData)
                },
                success: function(response) {
                    if (response.status === "success") {
                        alert("✅ " + response.message);
                        kosongkanKeranjang();

                        // 🔄 Muat ulang pending order
                        loadPendingOrders();
                    } else {
                        alert("❌ " + response.message);
                    }
                }
            });

        });


        // Panggil fungsi setelah halaman dimuat
        $(document).ready(function() {
            loadPendingOrders();
        });

        updateTotal();
    });

    function loadPendingOrders() {
        $.get("<?= site_url('kasir/load_pending_orders'); ?>", function(data) {
            let orders = JSON.parse(data);
            let html = '';
            if (orders.length === 0) {
                html = '<p class="text-muted text-center mt-2">Tidak ada pesanan</p>';
            } else {
                orders.forEach(function(order) {
                    html += `
                    <div class="pesanan-item" data-id="${order.id}">
                        <div><strong>${order.no_transaksi}</strong></div>
                        <div>${order.customer}</div>
                        <div>Rp ${parseInt(order.total_pembayaran).toLocaleString('id-ID')}</div>
                    </div>`;
                });
            }
            $('#pending-orders').html(html);
        });
    }

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

    $(document).ready(function() {
        let selectedOrderId = null; // Simpan ID pesanan yang dipilih
        loadPendingOrders();

        function formatRupiah(angka) {
            return "Rp " + parseInt(angka).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            });
        }

        function updateTotal() {
            let totalHarga = 0;
            $(".total").each(function() {
                totalHarga += parseInt($(this).text().replace("Rp ", "").replace(/\./g, "")) || 0;
            });

            let diskon = parseInt($("#nominal-diskon").text().replace("Rp ", "").replace(/\./g, "")) || 0;
            let totalBayar = totalHarga - diskon;

            $("#total-harga").text(formatRupiah(totalHarga));
            $("#nominal-diskon").text(formatRupiah(diskon));
            $("#total-bayar").text(formatRupiah(totalBayar > 0 ? totalBayar : 0));
        }



        $(document).on("click", ".pesanan-item", function() {
            $(".pesanan-item").removeClass("selected");
            $(this).addClass("selected");
            selectedOrderId = $(this).data("id");
        });

        // Klik tombol cetak
        $("#btn-cetak").click(function() {
            if (!selectedOrderId) {
                alert("Pilih pesanan terlebih dahulu!");
                return;
            }
            $("#modalCetak").modal("show");
        });

        // Pilih jenis cetakan
        $(".cetak-struk").click(function() {
            let cetakType = $(this).data("type");
            if (!selectedOrderId) {
                alert("Pilih pesanan terlebih dahulu!");
                return;
            }

            $.get("<?= site_url('kasir/cetak_struk/') ?>" + selectedOrderId + "/" + cetakType, function(
                response) {
                if (response.status === "success") {
                    alert("Cetakan berhasil dikirim ke printer: " + response.printer);
                } else {
                    alert("Gagal mencetak: " + response.message);
                }
            }, "json");
        });

        loadPendingOrders();
    });

    // Event klik tombol "Batal"
    $("#btn-batal").click(function() {
        $("#modalBatal").modal("show"); // Tampilkan modal konfirmasi
    });

    // Jika "OKE" diklik, kosongkan keranjang dan form input
    $("#confirmBatal").click(function() {
        $("#modalBatal").modal("hide");
        kosongkanKeranjang(); // Fungsi untuk mengosongkan keranjang
    });

    function kosongkanKeranjang() {
        $("#order-list").empty(); // Hapus semua item
        $("#total-harga").text("Rp 0");
        $("#nominal-diskon").text("Rp 0");
        $("#total-bayar").text("Rp 0");
        $("#nomor-meja").val("");
        $("#search-customer").val("");
        $("#walkin-customer-name").val("");
        $("#kode-voucher").val("");
        $("#voucher-message").text("");
        $("#customer-id").val("");
    }
    let currentProdukId = null;
    let extraData = {};

    $(document).on("click", ".btn-extra", function() {
        currentProdukId = $(this).data("id");

        $.get("<?= site_url('kasir/get_extra_list') ?>", function(data) {
            let list = JSON.parse(data);
            let html = "";
            list.forEach(extra => {
                html += `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input extra-check" data-id="${extra.id}"
                        data-sku="${extra.sku}" data-nama="${extra.nama_extra}" data-satuan="${extra.satuan}"
                        data-harga="${extra.harga}" data-hpp="${extra.hpp}" id="extra-${extra.id}">
                    <label class="form-check-label" for="extra-${extra.id}">
                        ${extra.nama_extra} - Rp ${parseInt(extra.harga).toLocaleString()}
                    </label>
                    <input type="number" class="form-control form-control-sm mt-1 jumlah-extra" data-id="${extra.id}" value="1" min="1" disabled>
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
            const jumlah = parseInt($(`.jumlah-extra[data-id=${id}]`).val());

            selected.push({
                id,
                nama,
                sku,
                satuan,
                harga,
                hpp,
                jumlah
            });
        });

        extraData[currentProdukId] = selected;

        let list = selected.map(e => `<li>${e.jumlah}x ${e.nama}</li>`).join("");
        $(`.list-extra[data-produk-id=${currentProdukId}]`).html(list);

        $("#modalExtra").modal("hide");
    });

    //klik kemudian cetak

    $(document).ready(function() {
        let selectedOrderId = null;

        function loadPrinters() {
            $.get("<?= site_url('printer/get_printers') ?>", function(response) {
                let options = "<option value=''>Pilih Printer</option>";
                response.forEach(printer => {
                    options +=
                        `<option value="${printer.printer_id}">${printer.lokasi_printer}</option>`;
                });
                $("#printerSelect").html(options);
            }, "json");
        }

        $("#btn-cetak").click(function() {
            loadPrinters();
            $("#modalCetak").modal("show");
        });

        $(".cetak-struk").click(function() {
            let selectedOrderId = $(".pesanan-item.selected").data("id");
            let cetakType = $(this).data("type");

            if (!selectedOrderId) {
                alert("Pilih pesanan terlebih dahulu!");
                return;
            }

            // URL harus memiliki 2 parameter: transaksi_id dan cetakType
            let url = "<?= site_url('kasir/cetak_struk/') ?>" + selectedOrderId + "/" + cetakType;

            window.open(url, "_blank");
        });

    });

    $(document).ready(function() {
        let selectedOrderId = null; // Simpan ID transaksi yang dipilih

        // Pilih pesanan
        $(document).on("click", ".pesanan-item", function() {
            $(".pesanan-item").removeClass("selected");
            $(this).addClass("selected");
            selectedOrderId = $(this).data("id"); // Simpan ID transaksi
        });

        // Klik tombol cetak
        $(".cetak-struk").click(function() {
            let selectedOrderId = $(".pesanan-item.selected").data("id");
            let cetakType = $(this).data("type");

            if (!selectedOrderId) {
                alert("Pilih pesanan terlebih dahulu!");
                return;
            }

            // URL harus memiliki 2 parameter: transaksi_id dan cetakType
            let url = "<?= site_url('kasir/cetak_struk/') ?>" + selectedOrderId + "/" + cetakType;

            window.open(url, "_blank");
        });

    });


    function printBluetooth(text) {
        if (window.PrintChannel) {
            window.PrintChannel.postMessage(text);
        } else {
            alert("Printer Bluetooth tidak tersedia.");
        }
    }

    // Gunakan ini saat klik tombol cetak di CI3
    document.getElementById("print-bluetooth").addEventListener("click", function() {
        let struk = "=== Struk POS ===\n";
        struk += "Nama: Namua Coffee & Eatery\n";
        struk += "--------------------------------\n";
        struk += "Total: Rp 55.000\n";
        struk += "--------------------------------\n";
        struk += "Terima Kasih!\n";

        printBluetooth(struk);
    });




    ///////////////////////////
    let selectedTransaksiId = null;

    function openModalCetak(transaksiId) {
        selectedTransaksiId = transaksiId;
        $('#modalCetak').modal('show');
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
        let printer = $('#printer_select').val();
        if (!printer) {
            alert('Pilih printer terlebih dahulu!');
            return;
        }

        $.ajax({
            url: base_url + 'kasir/cetak_struk/' + selectedTransaksiId + '/' + divisi,
            method: 'GET',
            data: {
                printer: printer
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    alert('Berhasil dikirim ke printer: ' + res.printer);
                } else {
                    alert('Gagal cetak: ' + res.message);
                }
            }
        });
    }

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
    </script>

</body>

</html>