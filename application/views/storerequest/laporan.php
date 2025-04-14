<div class="container">
    <h2>Laporan Store Request</h2>

    <!-- Filter Bulan -->
    <form method="GET" action="<?= site_url('storerequest/laporan'); ?>" class="form-inline mb-3">
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

    <?php if ($is_data_empty): ?>
        <p class="alert alert-warning">Data belum tersedia untuk bulan ini.</p>
    <?php else: ?>
        <!-- Tabel Laporan -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2" style="position: sticky; left: 0; background-color: #f8f9fa; z-index: 10;">Tanggal</th>
                        <th rowspan="2" style="text-align: center; background-color: #f8f9fa;">Jumlah</th> <!-- Kolom Jumlah -->
                        <?php foreach ($valid_jenis_pengeluaran as $jenis): ?>
                            <th style="text-align: center;"><?= $jenis['nama_jenis_pengeluaran'] ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menyiapkan array untuk memetakan jenis pengeluaran ke kolom
                    $laporan_terstruktur = [];
                    foreach ($laporan as $row) {
                        $laporan_terstruktur[$row['tanggal']][$row['nama_jenis_pengeluaran']] = $row['total_harga'];
                    }

                    // Menghitung tanggal dalam bulan
                    $start_date = strtotime($bulan . '-01');
                    $end_date = strtotime(date("Y-m-t", strtotime($bulan)));
                    $total_per_jenis_pengeluaran = array_fill(0, count($valid_jenis_pengeluaran), 0);
                    for ($date = $start_date; $date <= $end_date; $date += 86400) {
                        $tanggal = date('Y-m-d', $date);
                        echo '<tr>';
                        echo '<td style="position: sticky; left: 0; background-color: #ffffff;">' . date('d-m-Y', strtotime($tanggal)) . '</td>';

                        // Total per tanggal
                        $total_per_tanggal = 0;
                        foreach ($valid_jenis_pengeluaran as $index => $jenis) {
                            $total_harga = isset($laporan_terstruktur[$tanggal][$jenis['nama_jenis_pengeluaran']]) ?
                                           $laporan_terstruktur[$tanggal][$jenis['nama_jenis_pengeluaran']] : 0;
                            $total_per_tanggal += $total_harga;
                            $total_per_jenis_pengeluaran[$index] += $total_harga;
                        }

                        // Menampilkan total untuk setiap tanggal di kolom "Jumlah"
                        echo '<td class="text-right font-weight-bold">' . number_format($total_per_tanggal, 2) . '</td>';

                        // Menampilkan total per jenis pengeluaran
                        foreach ($valid_jenis_pengeluaran as $index => $jenis) {
                            $total_harga = isset($laporan_terstruktur[$tanggal][$jenis['nama_jenis_pengeluaran']]) ?
                                           $laporan_terstruktur[$tanggal][$jenis['nama_jenis_pengeluaran']] : 0;
                            echo '<td class="text-right">' . number_format($total_harga, 2) . '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <?php foreach ($total_per_jenis_pengeluaran as $total): ?>
                            <th class="text-right"><?= number_format($total, 2) ?></th>
                        <?php endforeach; ?>
                        <th class="text-right font-weight-bold"><?= number_format(array_sum($total_per_jenis_pengeluaran), 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
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
</style>
