<div class="container mt-4">
    <h4 class="mb-3 text-maroon"><i class="fas fa-file-alt"></i> Detail Refund</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="text-maroon mb-3">Info Transaksi</h5>
            <p><strong>No Transaksi:</strong> <?= $refund[0]->no_transaksi ?? '-' ?></p>
            <p><strong>Customer:</strong> <?= $refund[0]->customer ?? '-' ?></p>
            <p><strong>Meja:</strong> <?= $refund[0]->nomor_meja ?? '-' ?></p>
            <p><strong>Waktu Refund:</strong> <?= date('d/m/Y H:i', strtotime($refund[0]->waktu_refund)) ?></p>
            <p><strong>Metode Pembayaran:</strong> <?= $refund[0]->metode_pembayaran ?? '-' ?></p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="text-maroon mb-3">Produk yang Direfund</h5>
            <table class="table table-striped">
                <thead class="table-maroon text-center">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($refund as $r):
                        $subtotal = $r->jumlah * $r->harga;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= $r->nama_produk ?> <?= $r->nama_extra ? ' + ' . $r->nama_extra : '' ?></td>
                        <td class="text-center"><?= $r->jumlah ?></td>
                        <td class="text-end">Rp <?= number_format($r->harga, 0, ',', '.') ?></td>
                        <td class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Refund</th>
                        <th class="text-end text-maroon">Rp <?= number_format($total, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

            <h6 class="mt-4"><strong>Alasan:</strong> <?= $refund[0]->alasan ?? '-' ?></h6>
        </div>
    </div>

    <a href="<?= base_url('kasir/daftar_refund') ?>" class="btn btn-secondary">Kembali</a>
</div>

<style>
.text-maroon {
    color: maroon;
}

.table-maroon {
    background-color: #800000;
    color: white;
}
</style>