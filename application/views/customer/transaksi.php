<div class="container mt-4">
    <a href="<?= site_url('customer'); ?>" class="btn btn-link mb-3">← Kembali</a>
    <h4>Riwayat Transaksi Pelanggan</h4>
    <p>Pelanggan: <strong><?= $customer['kode_pelanggan']; ?> - <?= $customer['nama']; ?></strong></p>

    <div class="row mb-3">
        <div class="col-md-3"><input type="date" id="start_date" class="form-control"></div>
        <div class="col-md-3"><input type="date" id="end_date" class="form-control"></div>
        <div class="col-md-4"><input type="text" id="search" class="form-control" placeholder="Cari produk..."></div>
    </div>

    <div id="transaksi-wrapper"></div>
</div>

<script>
$(document).ready(function() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().slice(0, 10);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().slice(0, 10);
    $("#start_date").val(firstDay);
    $("#end_date").val(lastDay);

    function loadTransaksi() {
        const customerId = <?= $customer['id']; ?>;
        const start = $("#start_date").val();
        const end = $("#end_date").val();
        const search = $("#search").val();

        $.get("<?= site_url('customer/get_transaksi_detail_ajax'); ?>", {
            customer_id: customerId,
            start,
            end,
            search
        }, function(res) {
            const data = res;
            let html = "";

            if (data.length === 0) {
                html = `<div class="text-center text-muted">
                    <img src="<?= base_url('assets/img/no-data.svg') ?>" width="100"><br>
                    Tidak ada transaksi.
                </div>`;
            } else {
                data.forEach((trx, i) => {
                    html += `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-dark text-white d-flex justify-content-between">
                                <div>
                                    <strong>${trx.transaksi.no_transaksi}</strong> | ${trx.transaksi.tanggal}
                                </div>
                                <div>Total: <strong>Rp ${Number(trx.transaksi.total_penjualan).toLocaleString()}</strong></div>
                            </div>
                            <div class="card-body p-2">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light text-center">
                                        <tr><th>Produk</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>`;

                    trx.detail.forEach(row => {
                        html += `
                            <tr>
                                <td>${row.nama_produk}</td>
                                <td class="text-center">${row.jumlah}</td>
                                <td class="text-right">Rp ${Number(row.harga).toLocaleString()}</td>
                                <td class="text-right">Rp ${Number(row.subtotal).toLocaleString()}</td>
                            </tr>`;

                        if (row.extra.length > 0) {
                            row.extra.forEach(e => {
                                html += `
                                <tr class="table-secondary">
                                    <td class="ps-4">↳ ${e.nama_extra}</td>
                                    <td class="text-center">${e.jumlah}</td>
                                    <td class="text-right">Rp ${Number(e.harga).toLocaleString()}</td>
                                    <td class="text-right">Rp ${Number(e.subtotal).toLocaleString()}</td>
                                </tr>`;
                            });
                        }
                    });

                    html += `</tbody></table></div></div>`;
                });
            }

            $("#transaksi-wrapper").html(html);
        }, 'json');
    }

    loadTransaksi();
    $("#search, #start_date, #end_date").on("input change", loadTransaksi);
});
</script>