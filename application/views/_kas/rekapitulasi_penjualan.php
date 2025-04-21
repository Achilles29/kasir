<style>
    .nowrap-table td,
    .nowrap-table th {
        white-space: nowrap;
    }
</style>

<div class="container">
    <h2 class="text-center my-4">Rekapitulasi Penjualan</h2>

    <!-- Filter Tanggal -->
    <form method="get" action="<?= base_url('kas/rekapitulasi_penjualan') ?>" class="form-inline mb-4">
        <label for="tanggal_awal" class="mr-2">Tanggal Awal:</label>
        <input type="date" name="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control mr-3" required>
        
        <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
        <input type="date" name="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control mr-3" required>
        
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Rekapitulasi -->
    <table class="table table-bordered table-striped nowrap-table">
        <thead>
            <tr class="text-center">
                <th rowspan="2">Tanggal</th>
                <?php foreach ($rekening_list as $rekening): ?>
                    <th><?= $rekening['nama_rekening'] ?></th>
                <?php endforeach; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rekening_totals = array_fill_keys(array_column($rekening_list, 'nama_rekening'), 0); // Initialize totals for each rekening
            $grand_total = 0;
            ?>

            <?php foreach ($rekapitulasi as $tanggal => $data): ?>
                <tr>
                    <td class="text-center"><?= $tanggal ?></td>
                    <?php 
                    $row_total = 0;
                    foreach ($rekening_list as $rekening): 
                        $nominal = $data[$rekening['nama_rekening']] ?? 0;
                        $row_total += $nominal;
                        $rekening_totals[$rekening['nama_rekening']] += $nominal; // Add to rekening total
                    ?>
                        <td class="text-right"><?= $nominal > 0 ? 'Rp ' . number_format($nominal, 0, ',', '.') : '-' ?></td>
                    <?php endforeach; ?>
                    <td class="text-right font-weight-bold"><?= $row_total > 0 ? 'Rp ' . number_format($row_total, 0, ',', '.') : '-' ?></td>
                    <?php $grand_total += $row_total; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td class="text-center">Total</td>
                <?php foreach ($rekening_list as $rekening): ?>
                    <td class="text-right"><?= $rekening_totals[$rekening['nama_rekening']] > 0 ? 'Rp ' . number_format($rekening_totals[$rekening['nama_rekening']], 0, ',', '.') : '-' ?></td>
                <?php endforeach; ?>
                <td class="text-right"><?= $grand_total > 0 ? 'Rp ' . number_format($grand_total, 0, ',', '.') : '-' ?></td>
            </tr>
        </tfoot>
    </table>
</div>
