<div class="container-fluid">
    <h3 class="mb-4 text-center"><?= $title ?></h3>

    <div class="card shadow mb-4">
        <div class="card-header bg-maroon text-white">
            <strong>Informasi Umum</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="20%">No. Transaksi</th>
                    <td><?= $transaksi['no_transaksi'] ?></td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td><?= date('d/m/Y H:i', strtotime($transaksi['waktu_order'])) ?></td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td><?= $transaksi['customer'] ?: '-' ?></td>
                </tr>
                <tr>
                    <th>Nomor Meja</th>
                    <td><?= $transaksi['nomor_meja'] ?: '-' ?></td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td><span
                            class="badge badge-<?= $transaksi['status_pembayaran'] == 'LUNAS' ? 'success' : 'warning' ?>">
                            <?= $transaksi['status_pembayaran'] ?? 'BELUM BAYAR' ?>
                        </span></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-maroon text-white">
            <strong>Detail Item Transaksi</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="text-center" style="background-color: maroon; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($transaksi['items'] as $item): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $item['nama_produk'] ?></td>
                        <td class="text-center"><?= $item['jumlah'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($item['jumlah'] * $item['harga'], 0, ',', '.') ?>
                        </td>
                        <td><?= $item['catatan'] ?></td>
                    </tr>
                    <?php if (!empty($item['extra'])): ?>
                    <?php foreach ($item['extra'] as $ex): ?>
                    <tr>
                        <td></td>
                        <td colspan="2">↳ <?= $ex['jumlah'] ?>x <?= $ex['nama_extra'] ?></td>
                        <td class="text-right">Rp <?= number_format($ex['harga'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($ex['harga'] * $ex['jumlah'], 0, ',', '.') ?></td>
                        <td></td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mb-5">
        <a href="<?= site_url('kasir/transaksi_pending') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pending
        </a>
    </div>
</div>