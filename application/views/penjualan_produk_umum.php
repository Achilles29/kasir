<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan Produk Umum</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: right; /* Right-align numeric values */
        }
        th {
            text-align: center; /* Center-align the header */
            font-weight: bold; /* Make the header bold */
        }
        td {
            text-align: right; /* Right-align the body content */
        }
        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Data Penjualan Produk Umum</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>SKU</th>
                <th>Divisi</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Nilai</th>
                <th>Jumlah Refund</th>
                <th>Nilai Refund</th>
                <th>Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($penjualan as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['produk'] ?></td>
                <td><?= $row['sku'] ?></td>
                <td><?= $row['nama_divisi'] ?></td>
                <td><?= $row['kategori'] ?></td>
                <td><?= number_format($row['jumlah'], 2) ?></td>
                <td><?= number_format($row['nilai'], 2) ?></td>
                <td><?= number_format($row['jumlah_refund'], 2) ?></td>
                <td><?= number_format($row['nilai_refund'], 2) ?></td>
                <td><?= number_format($row['penjualan'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
