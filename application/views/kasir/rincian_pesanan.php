<style>
tbody tr {
    transition: all 0.5s ease;
}
</style>

<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-4">
                <i class="fas fa-receipt text-primary"></i> Rincian Pesanan
            </h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>No Transaksi:</strong> <?= $transaksi->no_transaksi ?></p>
                    <p><strong>Customer:</strong> <?= $transaksi->customer ?: '-' ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> <?= $transaksi->tanggal ?></p>
                    <p><strong>Total Bayar:</strong> <span class="badge badge-success p-2">
                            Rp <?= number_format($transaksi->total_pembayaran, 0, ',', '.') ?>
                        </span></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Extra</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><strong><?= $item->nama_produk ?></strong></td>
                            <td class="text-center"><?= $item->jumlah ?></td>
                            <td>Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($item->jumlah * $item->harga, 0, ',', '.') ?></td>
                            <td>
                                <?php if (!empty($item->extra)): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($item->extra as $ex): ?>
                                    <li><i class="fas fa-plus text-success"></i>
                                        <?= $ex->nama ?> (Rp <?= number_format($ex->harga, 0, ',', '.') ?>) x
                                        <?= $ex->jumlah ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm refund-produk" data-id="<?= $item->id ?>">
                                    <i class="fas fa-undo"></i> Refund
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-danger btn-lg" id="refund-semua">
                    <i class="fas fa-undo"></i> Refund Semua Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const base_url = "<?= base_url(); ?>";

$(document).ready(function() {
    // Refund per produk dengan transisi fade-out
    $(".refund-produk").click(function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        if (confirm("Apakah anda yakin ingin refund produk ini?")) {
            // Animasi fade-out baris
            row.fadeOut(500, function() {
                row.remove();
                // Setelah animasi selesai, baru redirect
                window.location.href = base_url + "kasir/refund_produk/" + id;
            });
        }
    });

    // Refund semua produk dengan transisi semua baris fade-out
    $("#refund-semua").click(function() {
        if (confirm("Apakah anda yakin ingin refund semua pesanan ini?")) {
            // Animasi semua baris fade-out
            $("tbody tr").fadeOut(500, function() {
                // Setelah semua baris hilang, redirect
                window.location.href = base_url + "kasir/refund_semua/<?= $transaksi->id ?>";
            });
        }
    });
});
</script>