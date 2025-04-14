<div class="container mt-4">
    <a href="<?= site_url('customer'); ?>" class="btn btn-link mb-3">← Kembali</a>
    <h4>Daftar Transaksi Pelanggan</h4>
    <p>Pelanggan: <?= $customer['kode_pelanggan']; ?> - <?= $customer['nama']; ?></p>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="Cari produk...">
        </div>
        <div class="col-md-4">
            <input type="date" id="start_date" class="form-control">
        </div>
        <div class="col-md-4">
            <input type="date" id="end_date" class="form-control">
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>TANGGAL</th>
                <th>PRODUK</th>
                <th >JUMLAH</th>
                <th >TOTAL TRANSAKSI (RP)</th>
            </tr>
        </thead>
        <tbody id="transaksi-list"></tbody>
    </table>

    <div class="text-center mt-4 text-muted" id="no-data" style="display:none;">
        <img src="<?= base_url('assets/img/no-data.svg') ?>" width="100"><br>
        <span>Data tidak tersedia</span>
    </div>
</div>
<script>
$(document).ready(function(){
    // Set default bulan ini
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().slice(0, 10);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().slice(0, 10);
    $("#start_date").val(firstDay);
    $("#end_date").val(lastDay);

    function loadTransaksi(){
        const customerId = <?= $customer['id']; ?>;
        const start = $("#start_date").val();
        const end = $("#end_date").val();
        const search = $("#search").val();

        $.ajax({
            url: "<?= site_url('customer/get_transaksi_ajax'); ?>",
            data: { customer_id: customerId, start: start, end: end, search: search },
            success: function(response) {
                let data = JSON.parse(response);
                let html = "";
                let total = 0;

                if (data.length > 0) {
                    data.forEach(row => {
                        total += parseFloat(row.subtotal);
                        html += `<tr>
                            <td>${row.tanggal}</td>
                            <td>${row.nama_produk}</td>
                            <td class="text-center">${row.jumlah}</td>
                            <td class="text-right">Rp ${Number(row.subtotal).toLocaleString()}</td>
                        </tr>`;
                    });

                    // Total baris
                    html += `<tr class="table-light font-weight-bold">
                        <td colspan="2" class="text-right">TOTAL</td>
                        <td class="text-right" colspan="1"></td>
                        <td class="text-right">Rp ${total.toLocaleString()}</td>
                    </tr>`;
                } else {
                    html = `<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>`;
                }

                $("#transaksi-list").html(html);
            }
        });
    }


    loadTransaksi();

    $("#search, #start_date, #end_date").on("change input", loadTransaksi);
});


</script>
