<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gudang Umum</title>
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
    <h1>Data Gudang Umum</h1>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Stok Awal</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
                <th>Stok Terbuang</th>
                <th>Stok Penyesuaian</th>
                <th>Stok Akhir</th>
                <th>Unit</th>
                <th>Harga</th>
                <th>Nilai Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gudang as $row): ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['merk'] ?></td>
                <td><?= $row['kategori'] ?></td>
                <td><?= $row['tipe'] ?></td>
                <td><?= number_format($row['stok_awal'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format($row['stok_masuk'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format($row['stok_keluar'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format($row['stok_terbuang'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format($row['stok_penyesuaian'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format($row['stok_akhir'] ?? 0, 0, ',', '.') ?></td>
                <td><?= $row['unit'] ?></td>
                <td><?= number_format($row['harga'] ?? 0, 0, ',', '.') ?></td>
                <td><?= number_format(($row['stok_akhir'] ?? 0) * ($row['harga'] ?? 0), 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
