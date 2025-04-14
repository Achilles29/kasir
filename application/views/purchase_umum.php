<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Purchase</title>
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
    <h1>Data Tabel: Purchase</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Pengeluaran</th>
                <th>Nama Barang</th>
                <th>Nama Bahan Baku</th>
                <th>Kategori</th>
                <th>Tipe Produksi</th>
                <th>Merk</th>
                <th>Keterangan</th>
                <th>Ukuran</th>
                <th>Unit</th>
                <th>Pack</th>
                <th>Harga Satuan</th>
                <th>Kuantitas</th>
                <th>Total Unit</th>
                <th>Total Harga</th>
                <th>HPP</th>
                <th>Metode Pembayaran</th>
                <th>Pengusul</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($purchases as $purchase): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= isset($purchase['tanggal']) ? $purchase['tanggal'] : 'N/A' ?></td>
                <td><?= isset($purchase['jenis_pengeluaran']) ? $purchase['jenis_pengeluaran'] : 'N/A' ?></td>
                <td><?= isset($purchase['nama_barang']) ? $purchase['nama_barang'] : 'N/A' ?></td>
                <td><?= isset($purchase['nama_bahan_baku']) ? $purchase['nama_bahan_baku'] : 'N/A' ?></td>
                <td><?= isset($purchase['kategori']) ? $purchase['kategori'] : 'N/A' ?></td>
                <td><?= isset($purchase['tipe_produksi']) ? $purchase['tipe_produksi'] : 'N/A' ?></td>
                <td><?= isset($purchase['merk']) ? $purchase['merk'] : 'N/A' ?></td>
                <td><?= isset($purchase['keterangan']) ? $purchase['keterangan'] : 'N/A' ?></td>
                <td><?= isset($purchase['ukuran']) ? $purchase['ukuran'] : 'N/A' ?></td>
                <td><?= isset($purchase['unit']) ? $purchase['unit'] : 'N/A' ?></td>
                <td><?= isset($purchase['pack']) ? $purchase['pack'] : 'N/A' ?></td>
                <td><?= isset($purchase['harga_satuan']) ? number_format($purchase['harga_satuan'], 2) : '0.00' ?></td>
                <td><?= isset($purchase['kuantitas']) ? number_format($purchase['kuantitas'], 2) : '0' ?></td>
                <td><?= isset($purchase['total_unit']) ? number_format($purchase['total_unit'], 2) : '0' ?></td>
                <td><?= isset($purchase['total_harga']) ? number_format($purchase['total_harga'], 2) : '0' ?></td>
                <td><?= isset($purchase['hpp']) ? number_format($purchase['hpp'], 2) : '0' ?></td>
                <td><?= isset($purchase['metode_pembayaran']) ? $purchase['metode_pembayaran'] : 'N/A' ?></td>
                <td><?= isset($purchase['pengusul']) ? $purchase['pengusul'] : 'N/A' ?></td>
                <td><?= isset($purchase['status']) ? $purchase['status'] : 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
