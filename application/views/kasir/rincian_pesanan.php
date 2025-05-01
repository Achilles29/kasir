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
                                    <li>
                                        <i class="fas fa-plus text-success"></i>
                                        <span class="extra-item" data-id="<?= $ex->pr_produk_extra_id ?? '' ?>">

                                            <?= $ex->nama ?> (Rp <?= number_format($ex->harga, 0, ',', '.') ?>)
                                        </span>
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

            <!-- Tombol Refund Semua -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal"
                    data-target="#modalAlasanRefundSemua">
                    <i class="fas fa-undo"></i> Refund Semua Pesanan
                </button>
            </div>
            <!-- Tombol Refund Pilihan -->
            <div class="text-center mt-2">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal"
                    data-bs-target="#modalRefundPilihan">
                    <i class="fas fa-check-square"></i> Refund Pilihan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Refund Pilihan -->
<div class="modal fade" id="modalRefundPilihan" tabindex="-1" role="dialog" aria-labelledby="modalRefundPilihanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formRefundPilihan" class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="modalRefundPilihanLabel"><i class="fas fa-undo"></i> Refund
                    Pilihan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="transaksi-id" value="<?= $transaksi->id ?>">
                <div id="listProdukVoid">
                    <!-- Checklist produk yang aktif akan ditampilkan lewat JS -->
                </div>
                <div class="form-group mt-2">
                    <label for="alasanRefundPilihan">Alasan Refund:</label>
                    <input type="text" class="form-control" name="alasan" id="alasanRefundPilihan" required
                        placeholder="Contoh: Refund beberapa item karena komplain">
                </div>
                <div class="form-group">
                    <label for="metode_refund">Pilih Metode Pengembalian:</label>
                    <select class="form-control" id="metode_refund_pilihan" name="metode_pembayaran_id" required>
                        <option value="">-- Pilih Metode --</option>
                        <?php foreach ($metode_pembayaran as $mp): ?>
                        <option value="<?= $mp->id ?>"><?= $mp->metode_pembayaran ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Proses Refund</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Alasan Refund Satuan -->

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
                <div class="form-group">
                    <label for="metode_refund">Pilih Metode Pengembalian:</label>
                    <select class="form-control" id="metode_refund_satuan" name="metode_pembayaran_id" required>
                        <option value="">-- Pilih Metode --</option>
                        <?php foreach ($metode_pembayaran as $mp): ?>
                        <option value="<?= $mp->id ?>"><?= $mp->metode_pembayaran ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Kirim Refund</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Refund Semua -->
<div class="modal fade" id="modalAlasanRefundSemua" tabindex="-1" role="dialog"
    aria-labelledby="modalAlasanRefundSemuaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formRefundSemua" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalAlasanRefundSemuaLabel"><i class="fas fa-undo"></i> Alasan Refund Semua
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="alasanSemua">Tuliskan alasan refund semua produk:</label>
                    <input type="text" class="form-control" name="alasanSemua" id="alasanSemua" required
                        placeholder="Contoh: Customer batalkan semua pesanan">
                </div>
                <div class="form-group">
                    <label for="metode_refund">Pilih Metode Pengembalian:</label>
                    <select class="form-control" id="metode_refund_semua" name="metode_pembayaran_id" required>
                        <option value="">-- Pilih Metode --</option>
                        <?php foreach ($metode_pembayaran as $mp): ?>
                        <option value="<?= $mp->id ?>"><?= $mp->metode_pembayaran ?></option>
                        <?php endforeach; ?>
                    </select>
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


<?php if (!empty($kode_refund)): ?>
<div class="alert alert-warning text-center">
    <strong>REFUND berhasil!</strong>
    <button class="btn btn-danger btn-sm ml-2" onclick="cetakRefundInternal('<?= $kode_refund ?>')">
        <i class="fas fa-print"></i> Cetak Refund (Internal)
    </button>
</div>
<?php endif; ?>


<script>
const base_url = "<?= base_url(); ?>";

$(document).ready(function() {
    // Buka modal alasan refund
    $(".refund-produk").click(function() {
        const id = $(this).data("id");
        $("#refund-id").val(id);
        $("#modalAlasanRefund").modal("show");
    });


    //refund satuan
    $("#formRefundAlasan").submit(function(e) {
        e.preventDefault();
        const id = $("#refund-id").val();
        const alasan = $(this).find("input[name='alasan']").val();
        const metode = $("#metode_refund_satuan").val();


        if (!metode) {
            Swal.fire("Metode kosong", "Silakan pilih metode pengembalian.", "warning");
            return;
        }

        $("#modalAlasanRefund").modal("hide");
        $("body").append('<div class="modal-backdrop fade show"></div>');

        setTimeout(function() {
            window.location.href =
                `${base_url}kasir/refund_produk/${id}?alasan=${encodeURIComponent(alasan)}&metode=${metode}`;
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
    // Refund semua produk
    $("#formRefundSemua").submit(function(e) {
        e.preventDefault();
        const metode = $("#metode_refund_semua").val();
        const alasan = $("#alasanSemua").val();


        $("#modalAlasanRefundSemua").modal("hide");
        $("body").append('<div class="modal-backdrop fade show"></div>');
        $("#loadingRefund").show();

        setTimeout(function() {
            window.location.href = base_url +
                "kasir/refund_semua/<?= $transaksi->id ?>?alasan=" + encodeURIComponent(
                    alasan) +
                "&metode=" + metode;
        }, 300);
    });

    // Refund Pilihan
    $("#modalRefundPilihan").on("show.bs.modal", function() {
        const html = [];

        $("table tbody tr").each(function() {
            const produkId = $(this).find(".refund-produk").data("id");
            const produkNama = $(this).find("td").eq(0).text();
            const harga = $(this).find("td").eq(2).text();

            if (produkId !== undefined) {
                // Buat checkbox untuk produk utama
                html.push(`
                <div class="form-check mb-2">
                    <input class="form-check-input refund-check" type="checkbox" name="produk_id[]" value="${produkId}" id="produk-${produkId}">
                    <label class="form-check-label" for="produk-${produkId}">
                        <strong>${produkNama}</strong> (${harga})
                    </label>
                </div>
            `);

                // Ambil extra dari tag <ul> extra di kolom ke-5
                const extraList = $(this).find("td").eq(4).find("li");
                extraList.each(function() {
                    const span = $(this).find("span.extra-item");
                    if (span.length > 0) {
                        const extraId = span.data("id");
                        const extraNama = span.text();
                        html.push(`
                        <div class="form-check ml-4 mb-2">
                            <input class="form-check-input refund-check" type="checkbox" name="extra_id[]" value="${extraId}" id="extra-${extraId}">
                            <label class="form-check-label text-muted" for="extra-${extraId}">
                                <i class="fas fa-plus text-success"></i> ${extraNama}
                            </label>
                        </div>
                    `);
                    }
                });
            }
        });

        $("#listProdukVoid").html(html.join(""));
    });



    $("#formRefundPilihan").submit(function(e) {
        e.preventDefault();

        const selectedProduk = $("input[name='produk_id[]']:checked").map(function() {
            return $(this).val();
        }).get();

        const selectedExtra = $("input[name='extra_id[]']:checked").map(function() {
            return $(this).val();
        }).get();

        const alasan = $("#alasanRefundPilihan").val();
        const transaksiId = $("#transaksi-id").val();
        const metodeId = $("#metode_refund_pilihan").val();

        if (selectedProduk.length === 0 && selectedExtra.length === 0) {
            alert("Pilih produk atau extra terlebih dahulu.");
            return;
        }

        $("#modalRefundPilihan").modal("hide");
        $("#loadingRefund").show();

        $.post(base_url + "kasir/refund_pilihan", {
            produk_ids: selectedProduk,
            extra_ids: selectedExtra,
            alasan: alasan,
            transaksi_id: transaksiId,
            metode_pembayaran_id: metodeId
        }, function(kode_refund) {
            window.location.href = base_url + "kasir/rincian_pesanan/" + transaksiId +
                "?kode_refund=" + kode_refund;
        });
    });


    function cetakRefundInternal(kode_refund) {
        $.post(base_url + "kasir/cetak_refund_internal", {
            kode_refund: kode_refund
        }, function(res) {
            if (res.status === 'success') {
                Swal.fire('Berhasil', res.message, 'success');
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        }, "json");
    }


});
</script>