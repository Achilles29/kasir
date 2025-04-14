<div class="container">
    <h2><?= $title ?></h2>

    <!-- Filter -->
    <form method="get" class="form-inline mb-3">
        <label for="month" class="mr-2">Bulan</label>
        <select id="month" name="month" class="form-control mr-3">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
            <?php endfor; ?>
        </select>

        <label for="year" class="mr-2">Tahun</label>
        <select id="year" name="year" class="form-control mr-3">
            <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                <option value="<?= $i ?>" <?= $i == $year ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Saldo Kas -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <?php foreach ($saldo_awal as $rekening): ?>
                        <th><?= $rekening['nama_rekening'] ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Saldo Awal</td>
                    <?php 
                    $saldo = [];
                    $total_awal = 0;
                    foreach ($saldo_awal as $rekening) {
                        $saldo[$rekening['rekening_id']] = $rekening['saldo_awal'];
                        $total_awal += $rekening['saldo_awal'];
                        echo "<td>" . number_format($rekening['saldo_awal'], 2) . "</td>";
                    }
                    ?>
                    <td><?= number_format($total_awal, 2) ?></td>
                </tr>
                <?php 
                $total_column = [];
                foreach ($saldo_berjalan as $row): 
                    if (!isset($total_column[$row['rekening_id']])) $total_column[$row['rekening_id']] = $saldo[$row['rekening_id']];
                    $total_column[$row['rekening_id']] += $row['penjualan'] - $row['pembelian'] + $row['mutasi_masuk'] - $row['mutasi_keluar'];
                ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <?php foreach ($saldo_awal as $rekening): ?>
                            <td><?= isset($total_column[$rekening['rekening_id']]) ? number_format($total_column[$rekening['rekening_id']], 2) : '-' ?></td>
                        <?php endforeach; ?>
                        <td><?= number_format(array_sum($total_column), 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
