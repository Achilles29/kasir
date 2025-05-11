<div class="container mt-4">
    <a href="<?= site_url('customer') ?>" class="btn btn-outline-secondary mb-3">
        ← Kembali ke Daftar Pelanggan
    </a>

    <h4 class="mb-3">Riwayat Poin Pelanggan</h4>
    <p>
        <strong><?= $customer['kode_pelanggan']; ?> - <?= $customer['nama']; ?></strong>
    </p>

    <!-- RINGKASAN POIN -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1">Poin Aktif</h5>
                    <h3><?= $poin_aktif ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1">Poin Terpakai</h5>
                    <h3><?= $poin_terpakai ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1">Poin Kadaluarsa</h5>
                    <h3><?= $poin_kadaluarsa ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1">Akan Kadaluarsa (30 hari)</h5>
                    <h3><?= $poin_akan_kadaluarsa ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- RIWAYAT POIN -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>No Transaksi</th>
                    <th>Jenis</th>
                    <th>Sumber</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Kedaluwarsa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($poin) > 0): ?>
                <?php foreach ($poin as $p): ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($p['created_at'])) ?></td>
                    <td><?= $p['no_transaksi'] ?? '-' ?></td>
                    <td><?= ucfirst($p['jenis']) ?></td>
                    <td><?= ucfirst($row['sumber'] ?? '-') ?> </td>
                    <td><?= $p['jumlah_poin'] ?></td>
                    <td>
                        <span class="badge 
                        <?= $p['status'] === 'aktif' ? 'bg-success' : 
                            ($p['status'] === 'terpakai' ? 'bg-warning' : 'bg-danger') ?>">
                            <?= ucfirst($p['status']) ?>
                        </span>
                    </td>
                    <td><?= $p['tanggal_kedaluwarsa'] ? date('d-m-Y', strtotime($p['tanggal_kedaluwarsa'])) : '-' ?>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada riwayat poin ditemukan.</td>
                </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>