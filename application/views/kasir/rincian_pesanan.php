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
                                        <?= $ex->nama ?> (Rp <?= number_format($ex->harga, 0, ',', '.') ?>)

                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($item->status == 'REFUND'): ?>
                                <span class="badge badge-danger">REFUND</span>
                                <?php else: ?>
                                <button class="btn btn-warning btn-sm refund-produk" data-id="<?= $item->id ?>">
                                    <i class="fas fa-undo"></i> Refund
                                </button>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-danger btn-lg" id="refund-semua">
                    <i class="fas fa-undo"></i> Refund Semua Pesanan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Alasan Refund -->
<div class="modal fade" id="modalAlasanRefund" tabindex="-1" role="dialog" aria-labelledby="modalAlasanRefundLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formRefundAlasan" class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalAlasanRefundLabel"><i class="fas fa-undo"></i> Alasan Refund</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="refund-id" name="id">
                <div class="form-group">
                    <label for="alasan">Tuliskan alasan refund:</label>
                    <input type="text" class="form-control" name="alasan" required
                        placeholder="Contoh: Salah input / Komplain customer">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Kirim Refund</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="loadingRefund"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:#ffffffcc; z-index:9999; text-align:center; padding-top:20%;">
    <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>
    <p class="mt-3 text-danger">Memproses refund...</p>
</div>


<script>
const base_url = "<?= base_url(); ?>";

$(document).ready(function() {
    // Buka modal alasan refund
    $(".refund-produk").click(function() {
        const id = $(this).data("id");
        $("#refund-id").val(id);
        $("#modalAlasanRefund").modal("show");
    });

    // Kirim alasan refund
    $("#formRefundAlasan").submit(function(e) {
        e.preventDefault();
        const id = $("#refund-id").val();
        const alasan = $(this).find("input[name='alasan']").val();

        // Loading smooth
        $("#modalAlasanRefund").modal("hide");
        $("body").append('<div class="modal-backdrop fade show"></div>');

        // Delay 300ms baru redirect
        setTimeout(function() {
            window.location.href =
                `${base_url}kasir/refund_produk/${id}?alasan=${encodeURIComponent(alasan)}`;
        }, 300);
    });

    // Refund semua (langsung tanpa alasan individu)
    $("#refund-semua").click(function() {
        if (confirm("Apakah anda yakin ingin refund semua pesanan ini?")) {
            $("tbody tr").fadeOut(300);
            setTimeout(function() {
                window.location.href = base_url + "kasir/refund_semua/<?= $transaksi->id ?>";
            }, 300);
        }
    });
});
</script>