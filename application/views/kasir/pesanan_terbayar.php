<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan Terbayar</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Pesanan Terbayar</h2>

        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" id="tanggal-awal" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" id="tanggal-akhir" class="form-control">
            </div>
            <div class="col-md-4">
                <input type="text" id="search" class="form-control" placeholder="Cari Nomor Transaksi / Customer">
            </div>
            <div class="col-md-2">
                <button id="filter" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>

        <table class="table table-bordered" id="tabel-pesanan">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>No Transaksi</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
    const base_url = "<?= base_url(); ?>";

    function loadPesanan() {
        const tanggal_awal = $("#tanggal-awal").val();
        const tanggal_akhir = $("#tanggal-akhir").val();
        const search = $("#search").val();

        $.get(base_url + "kasir/get_pesanan_terbayar", {
            tanggal_awal: tanggal_awal,
            tanggal_akhir: tanggal_akhir,
            search: search
        }, function(res) {
            const data = JSON.parse(res);
            let html = "";
            data.forEach((item, index) => {
                html += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.no_transaksi}</td>
                <td>${item.customer || '-'}</td>
                <td>${item.tanggal}</td>
                <td>Rp ${parseInt(item.total_pembayaran).toLocaleString('id-ID')}</td>
                <td>${item.status_pembayaran}</td>
                <td>
                    <button class="btn btn-info btn-sm lihat-rincian" data-id="${item.id}">
                        Lihat
                    </button>
                </td>
            </tr>
            `;
            });

            $("#tabel-pesanan tbody").html(html);
        });
    }

    $(document).ready(function() {
        loadPesanan();

        $("#filter").click(function() {
            loadPesanan();
        });

        $("#tabel-pesanan").on("click", ".lihat-rincian", function() {
            const id = $(this).data("id");
            window.open(base_url + "kasir/rincian_pesanan/" + id, "_blank");
        });
    });
    </script>

</body>

</html>