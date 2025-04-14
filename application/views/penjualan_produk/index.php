<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan Produk</title>
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
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Data Tabel: Penjualan Produk</h1>




<form method="get" action="<?= base_url('penjualan_produk/index') ?>" class="form-inline mb-3">
    <label for="tanggal_awal">Tanggal Awal:</label>
    <input type="date" name="tanggal_awal" id="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control">
    
    <label for="tanggal_akhir">Tanggal Akhir:</label>
    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control">

    <label for="divisi">Divisi:</label>
    <select name="divisi" id="divisi" class="form-control">
        <option value="">Semua</option>
        <?php foreach ($divisi_list as $divisi): ?>
            <option value="<?= $divisi['id'] ?>" <?= $divisi['id'] == $divisi ? 'selected' : '' ?>><?= $divisi['nama_divisi'] ?></option>
        <?php endforeach; ?>
    </select>
    
    <label for="search">Cari Produk:</label>
    <input type="text" name="search" id="search" value="<?= $search ?>" class="form-control" onkeyup="searchProduk()">

    <button type="submit" class="btn btn-primary">Filter</button>
    
    <label for="per_page">Tampilkan</label>
    <select id="per_page" name="per_page" class="form-control" onchange="this.form.submit()">
        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
        <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30</option>
        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
    </select>
</form>
<div class="table-responsive">
 <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Tanggal</th>
            <th style="text-align: center;">Produk</th>
            <th style="text-align: center;">SKU</th>
            <th style="text-align: center;">Divisi</th>
            <th style="text-align: center;">Kategori</th>
            <th style="text-align: center;">Jumlah</th>
            <th style="text-align: center;">Nilai</th>
            <th style="text-align: center;">Jumlah Refund</th>
            <th style="text-align: center;">Nilai Refund</th>
            <th style="text-align: center;">Penjualan</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = $start + 1; foreach ($penjualan as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['produk'] ?></td>
            <td><?= $row['sku'] ?></td>
            <td><?= $row['nama_divisi'] ?></td>
            <td><?= $row['kategori'] ?></td>
            <td style="text-align: right;"><?= number_format($row['jumlah'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['nilai'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['jumlah_refund'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['nilai_refund'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['penjualan'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"><strong>Total:</strong></td>
            <td style="text-align: right;"><strong><?= number_format($totals['total_jumlah'], 2) ?></strong></td>
            <td style="text-align: right;"><strong><?= number_format($totals['total_nilai'], 2) ?></strong></td>
            <td style="text-align: right;"><strong><?= number_format($totals['total_jumlah_refund'], 2) ?></strong></td>
            <td style="text-align: right;"><strong><?= number_format($totals['total_nilai_refund'], 2) ?></strong></td>
            <td style="text-align: right;"><strong><?= number_format($totals['total_penjualan'], 2) ?></strong></td>
        </tr>
    </tfoot>
</table>
</div>

    <div class="pagination"><?= $pagination ?></div>

    <script>
        function searchProduk() {
            let search = document.getElementById('search').value;
            fetch('<?= base_url("Penjualan_produk/search_produk") ?>?search=' + search)
                .then(response => response.json())
                .then(data => {
                    // Update the search input with filtered products
                    let searchInput = document.getElementById('search');
                    let suggestions = data.map(produk => produk.nama_produk).join(', '); // Show names separated by commas
                    searchInput.setAttribute('placeholder', suggestions); // Show suggestions as placeholder text
                });
        }
    </script>
</body>
</html>
