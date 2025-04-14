<style>
    .text-right {
    text-align: right;
}

.text-success {
    color: green;
}

.text-danger {
    color: red;
}

.font-weight-bold {
    font-weight: bold;
}
</style>

<div class="container mt-4">
        <h1 class="text-center">Laporan Keuangan</h1>
            <form method="GET" action="<?= site_url('laporan_keuangan'); ?>">
                <label for="bulan">Pilih Bulan:</label>
                <select id="bulan" name="bulan" required>
                    <?php 
                    $selected_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // Ambil dari GET request jika ada
                    for ($i = 1; $i <= 12; $i++): 
                        $month = sprintf('%02d', $i);
                        $selected = ($selected_bulan == $month) ? 'selected' : '';
                    ?>
                        <option value="<?= $month; ?>" <?= $selected; ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 10)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                        
                <label for="tahun">Pilih Tahun:</label>
                <select id="tahun" name="tahun" required>
                    <?php for ($i = date('Y') - 5; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i; ?>" <?= date('Y') == $i ? 'selected' : ''; ?>><?= $i; ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
            <table class="table table-striped table-bordered mt-3">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Penjualan</th>
                        <th class="text-center">Refund</th>
                        <th class="text-center">Pengeluaran</th>
                        <th class="text-success text-center">Pendapatan Kotor</th>
                        <th class="text-center">Estimasi Gaji</th>
                        <th class="text-danger text-center">Estimasi Pendapatan Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $row): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                        <td class="text-right"><?= number_format($row['penjualan'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($row['refund'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($row['pengeluaran'], 0, ',', '.') ?></td>
                        <td class="text-right text-success font-weight-bold"><?= number_format($row['pendapatan_kotor'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($row['estimasi_gaji'], 0, ',', '.') ?></td>
                        <td class="text-right text-danger font-weight-bold"><?= number_format($row['estimasi_pendapatan_final'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?= number_format(array_sum(array_column($laporan, 'penjualan')), 0, ',', '.') ?></th>
                        <th class="text-right"><?= number_format(array_sum(array_column($laporan, 'refund')), 0, ',', '.') ?></th>
                        <th class="text-right"><?= number_format(array_sum(array_column($laporan, 'pengeluaran')), 0, ',', '.') ?></th>
                        <th class="text-right text-success font-weight-bold"><?= number_format(array_sum(array_column($laporan, 'pendapatan_kotor')), 0, ',', '.') ?></th>
                        <th class="text-right"><?= number_format(array_sum(array_column($laporan, 'estimasi_gaji')), 0, ',', '.') ?></th>
                        <th class="text-right text-danger font-weight-bold"><?= number_format(array_sum(array_column($laporan, 'estimasi_pendapatan_final')), 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

    </div>
