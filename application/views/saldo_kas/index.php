<div class="container-fluid">
    <h2>Saldo Kas Berjalan</h2>
    <form method="get" action="<?= base_url('saldo_kas') ?>">
        <label for="bulan">Bulan:</label>
        <select id="bulan" name="bulan">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $bulan == $i ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 1)) ?></option>
            <?php endfor; ?>
        </select>

        <label for="tahun">Tahun:</label>
        <input type="number" id="tahun" name="tahun" value="<?= $tahun ?>">

        <button type="submit">Filter</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <?php foreach ($kas_berjalan as $rekening): ?>
                    <th><?= $rekening['nama_rekening'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $tanggal_awal = date('Y-m-d', strtotime("$tahun-$bulan-01"));
            $tanggal_akhir = date('Y-m-t', strtotime("$tahun-$bulan-01"));

            for ($tanggal = strtotime($tanggal_awal); $tanggal <= strtotime($tanggal_akhir); $tanggal = strtotime('+1 day', $tanggal)) {
                $tanggal_str = date('Y-m-d', $tanggal);
                echo '<tr>';
                echo '<td>' . $tanggal_str . '</td>';
                foreach ($kas_berjalan as $rekening_id => $rekening) {
                    echo '<td>' . number_format($rekening['transaksi'][$tanggal_str] ?? 0, 2) . '</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
