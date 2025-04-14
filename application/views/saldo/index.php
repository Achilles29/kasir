<div class="container">
    <h2>Saldo Berjalan</h2>

    <!-- Filter Form -->
    <form class="form-inline" method="get" action="<?= base_url('saldo') ?>">
        <label for="rekening_id" class="mr-2">Rekening:</label>
        <select name="rekening_id" id="rekening_id" class="form-control mr-3">
            <option value="">Pilih Rekening</option>
            <?php foreach ($rekening_list as $rekening): ?>
                <option value="<?= $rekening['id'] ?>" <?= $rekening_id == $rekening['id'] ? 'selected' : '' ?>>
                    <?= $rekening['nama_rekening'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="bulan" class="mr-2">Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-3">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $bulan == $i ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="tahun" class="mr-2">Tahun:</label>
        <select name="tahun" id="tahun" class="form-control mr-3">
            <?php for ($i = date('Y') - 5; $i <= date('Y'); $i++): ?>
                <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    <hr>

    <!-- Tabel Saldo Berjalan -->
    <?php if (!empty($saldo_berjalan)): ?>
        <h4>Saldo Awal: Rp <?= number_format($saldo_awal, 2) ?></h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Penjualan</th>
                    <th>Mutasi Masuk</th>
                    <th>Mutasi Keluar</th>
                    <th>Pembelian</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($saldo_berjalan as $row): ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <td>Rp <?= number_format($row['penjualan'], 2) ?></td>
                        <td>Rp <?= number_format($row['mutasi_masuk'], 2) ?></td>
                        <td>Rp <?= number_format($row['mutasi_keluar'], 2) ?></td>
                        <td>Rp <?= number_format($row['pembelian'], 2) ?></td>
                        <td>Rp <?= number_format($row['saldo'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Silakan pilih rekening dan filter untuk melihat data saldo berjalan.</p>
    <?php endif; ?>
</div>
