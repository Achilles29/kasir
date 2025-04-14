<!DOCTYPE html>
<html>
<head>
    <title>Daily Inventory</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Daily Inventory</h1>

    <form method="get" action="">
        <label>Bulan:</label>
        <select name="month">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?php echo sprintf('%02d', $i); ?>" <?php echo $month == $i ? 'selected' : ''; ?>>
                    <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>
        <label>Tahun:</label>
        <input type="number" name="year" value="<?php echo $year; ?>">
        <button type="submit">Tampilkan</button>
    </form>
    <br>
    <a href="<?php echo site_url('inventory/generate?month=' . $month . '&year=' . $year); ?>">Generate Stok Harian</a>
<br>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Merk</th>
                <th rowspan="2">Unit</th>
                <th rowspan="2">Stok Awal</th>
                <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                    <th colspan="4"><?php echo $day; ?></th>
                <?php endfor; ?>
            </tr>
            <tr>
                <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Terbuang</th>
                    <th>Akhir</th>
                <?php endfor; ?>
            </tr>
        </thead>
    <tbody>
        <?php if (empty($daily_inventory)): ?>
            <tr>
                <td colspan="<?php echo 4 + ($days_in_month * 4); ?>">Tidak ada data untuk bulan ini.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($daily_inventory as $item): ?>
            <tr>
                <td><?php echo $item['nama_barang']; ?></td>
                <td><?php echo $item['merk']; ?></td>
                <td><?php echo $item['unit']; ?></td>
                <td><?php echo $item['stok_awal']; ?></td>
                <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                    <?php
                        $tanggal = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $stok_masuk = $item['stok_masuk_' . $tanggal] ?? 0;
                        $stok_keluar = $item['stok_keluar_' . $tanggal] ?? 0;
                        $stok_terbuang = $item['stok_terbuang_' . $tanggal] ?? 0;
                        $stok_akhir = $item['stok_akhir_' . $tanggal] ?? 0;
                    ?>
                    <td><?php echo $stok_masuk; ?></td>
                    <td><?php echo $stok_keluar; ?></td>
                    <td><?php echo $stok_terbuang; ?></td>
                    <td><?php echo $stok_akhir; ?></td>
                <?php endfor; ?>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>

    </table>
</body>
</html>
