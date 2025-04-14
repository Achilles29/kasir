<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan Kasir Umum</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Data Tabel: Penjualan Kasir Umum</h1>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No Nota</th>
                <th>Waktu Order</th>
                <th>Waktu Bayar</th>
                <th>Penjualan (Rp)</th>
                <th>Metode Pembayaran</th>
                <th>Rekening</th>
                <th>Penyesuaian</th>
                <th>Selisih</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($penjualan as $row): ?>
            <tr>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['no_nota'] ?></td>
                <td><?= $row['waktu_order'] ?></td>
                <td><?= $row['waktu_bayar'] ?></td>
                <td class="text-right"><?= number_format($row['penjualan'], 2) ?></td>
                <td><?= $row['metode_pembayaran'] ?></td>
                <td><?= $row['rekening'] ?></td>
                <td class="text-right"><?= number_format($row['penyesuaian'], 2) ?></td>
                <td class="text-right"><?= number_format($row['selisih'], 2) ?></td>
                <td><?= $row['keterangan'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
