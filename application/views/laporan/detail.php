<div class="container mt-4">
    <h4 class="mb-4">
        <i class="fas fa-file-invoice text-primary"></i> Detail Transaksi
    </h4>

    <!-- RINGKASAN TRANSAKSI -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>No Transaksi:</strong> <?= $transaksi['no_transaksi'] ?></p>
            <p><strong>Tanggal Order:</strong> <?= $transaksi['waktu_order'] ?></p>
            <p><strong>Total Penjualan:</strong> Rp <?= number_format($transaksi['total_penjualan'], 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- DETAIL PRODUK -->
    <h5 class="mb-2">Detail Produk</h5>
    <table class="table table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_grouped as $d): ?>
            <tr>
                <td>
                    <?= $d['nama_produk'] ?>
                    <?php if (!empty($d['catatan'])): ?>
                    <br><small class="text-muted">Catatan: <?= $d['catatan'] ?></small>
                    <?php endif; ?>
                    <?php if (!empty($d['extra'])): ?>
                    <?php foreach ($d['extra'] as $extra_nama => $qty): ?>
                    <div class="text-secondary ms-2">+ <?= $extra_nama ?> (x<?= $qty ?>)</div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td><?= $d['jumlah'] ?></td>
                <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <!-- DETAIL PEMESANAN -->
    <h5 class="mt-4">🧾 Detail Pemesanan</h5>
    <table class="table table-bordered">
        <tr>
            <th>Order</th>
            <td><?= $kasir_order ?></td>
        </tr>
        <tr>
            <th>Kasir</th>
            <td><?= $kasir_bayar ?></td>
        </tr>
        <tr>
            <th>Nomor Meja</th>
            <td><?= $transaksi['nomor_meja'] ?: '-' ?></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td><?= $transaksi['customer'] ?: '-' ?></td>
        </tr>
        <tr>
            <th>Poin Didapat</th>
            <td><?= $poin_didapat ?></td>
        </tr>
        <tr>
            <th>Total Poin Customer</th>
            <td><?= $total_poin ?></td>
        </tr>
    </table>

    <!-- PEMBAYARAN -->
    <h5 class="mb-2">Detail Pembayaran</h5>
    <ul>
        <?php foreach ($pembayaran as $p): ?>
        <li><?= $p['metode_pembayaran'] ?> - Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- REFUND -->
    <?php if ($refund): ?>
    <div class="alert alert-warning mt-3">
        <strong>Refund:</strong> Rp
        <?= isset($refund['total_refund']) ? number_format($refund['total_refund'], 0, ',', '.') : '0' ?><br>
        <span>Alasan: <?= $refund['alasan'] ?></span>
    </div>
    <?php endif; ?>

    <!-- VOID -->
    <?php if ($void): ?>
    <div class="alert alert-danger mt-3">
        <strong>Void:</strong> Rp <?= number_format($void['total_void'] ?? 0, 0, ',', '.') ?><br>
        Alasan: <?= $void['alasan'] ?>
    </div>
    <?php endif; ?>
</div>