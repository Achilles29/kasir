<h4 class="mb-3"><i class="fas fa-clock text-primary"></i> Riwayat Shift Kasir</h4>

<form method="get" class="form-inline mb-4">
    <label class="mr-2">Filter Tanggal:</label>
    <input type="date" name="tanggal_awal" class="form-control form-control-sm mr-2" value="<?= $tanggal_awal ?>">
    <input type="date" name="tanggal_akhir" class="form-control form-control-sm mr-2" value="<?= $tanggal_akhir ?>">
    <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle shadow-sm">
        <thead class="thead-dark">
            <tr>
                <th>Kasir</th>
                <th>Waktu Buka</th>
                <th>Waktu Tutup</th>
                <th>Total Penjualan</th>
                <th>Saldo Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($shifts)): ?>
            <tr>
                <td colspan="6" class="text-muted">Tidak ada data shift pada rentang ini.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($shifts as $s): ?>
            <tr>
                <td><span class="badge badge-info text-uppercase px-2 py-1"><?= $s['nama_kasir'] ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($s['waktu_mulai'])) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($s['waktu_tutup'])) ?></td>
                <td class="text-right text-success">Rp <?= number_format($s['total_penjualan'], 0, ',', '.') ?></td>
                <td class="text-right text-primary font-weight-bold">Rp
                    <?= number_format($s['modal_akhir'], 0, ',', '.') ?></td>
                <td>
                    <a href="<?= base_url('kasir/cetak_laporan_shift/' . $s['id']) ?>"
                        class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>