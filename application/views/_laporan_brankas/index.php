<div class="container">
    <h2>Laporan Brankas</h2>

    <!-- Filter Bulan (Month and Year dropdown) -->
    <form method="GET" action="<?= site_url('laporan_brankas'); ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Pilih Bulan:</label>
        <select id="bulan" name="bulan" class="form-control mr-3">
            <?php 
            $current_month = date('m');
            $current_year = date('Y');
            for ($i = 1; $i <= 12; $i++) :
                $month = sprintf('%02d', $i);
                $selected = $bulan == "$current_year-$month" ? 'selected' : '';
                echo "<option value='$current_year-$month' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . " $current_year</option>";
            endfor;
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Terapkan</button>
    </form>

    <!-- Tabel Laporan -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2" style="position: sticky; left: 0; background-color: #f8f9fa; z-index: 10;">Tanggal</th>
                    <th rowspan="2" style="text-align: center;">Saldo Awal</th>
                    <th rowspan="2" style="text-align: center;">Pendapatan</th>
                    <th rowspan="2" style="text-align: center;">Mutasi Kas</th>
                    <th rowspan="2" style="text-align: center;">Refund</th>
                    <th rowspan="2" style="text-align: center;">Belanja</th>
                    <th rowspan="2" style="text-align: center;">Total Brankas</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $saldo_awal = 0; // Starting saldo for the first row

                foreach ($brankas_data as $row): 
                    // Calculate the saldo for the next row based on Total Brankas
                    if ($saldo_awal == 0) {
                        $saldo_awal = $row['saldo_awal']; // For the first row, fetch saldo from bl_kas
                    } else {
                        // For subsequent rows, set saldo as the previous Total Brankas
                        $saldo_awal = $previous_total_brankas;
                    }

                    // Calculate total brankas for the current row
                    $total_brankas = $saldo_awal + $row['pendapatan'] + $row['mutasi_kas'] - $row['refund'] - $row['belanja'];
                    $previous_total_brankas = $total_brankas; // Save it for the next iteration
                ?>
                <tr>
                    <td><?= $row['tanggal'] ?></td>
                    <td class="text-right"><?= number_format($saldo_awal, 2) ?></td>
                    <td class="text-right"><?= number_format($row['pendapatan'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['mutasi_kas'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['refund'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['belanja'], 2) ?></td>
                    <td class="text-right"><?= number_format($total_brankas, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Additional Styles for Sticky Table -->
<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }
    td, th {
        vertical-align: middle;
    }
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }

    /* Sticky Tanggal Column */
    tbody td:first-child, thead th:first-child {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 10;
    }
</style>
