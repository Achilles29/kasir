<!DOCTYPE html>
<html>
<head>
    <title>Data Perbelanjaan</title>
</head>
<body>
    <h1>Data Perbelanjaan</h1>

    <form action="<?= base_url('perbelanjaan/add') ?>" method="POST">
    <label>Tanggal:</label>
    <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required><br>

    <label>Jenis Pengeluaran:</label>
    <select name="jenis_pengeluaran" required>
        <?php foreach ($jenis_pengeluaran as $jp): ?>
            <option value="<?= $jp['id'] ?>" <?= $jp['id'] == 1 ? 'selected' : '' ?>><?= $jp['nama_pengeluaran'] ?></option>
        <?php endforeach; ?>
    </select><br>


<label>Cari Barang:</label>
<input type="text" id="search-belanja" placeholder="Cari barang..." autocomplete="off">
<ul id="search-results" style="list-style: none; padding: 0; max-height: 150px; overflow-y: auto; border: 1px solid #ddd; display: none;"></ul><br>
<input type="hidden" id="selected-belanja-id" name="selected_belanja_id">



    <label>Nama Barang:</label>
    <input type="text" name="nama_barang" id="nama_barang" required><br>

    <label>Nama Bahan Baku:</label>
    <input type="text" name="nama_bahan_baku" id="nama_bahan_baku"><br>

    <label>Tipe:</label>
    <input type="text" name="tipe" id="tipe"><br>

    <label>Merk:</label>
    <input type="text" name="merk" id="merk"><br>

    <label>Ukuran:</label>
    <input type="number" name="ukuran" id="ukuran" step="0.01" required><br>

    <label>Unit:</label>
    <input type="text" name="unit" id="unit"><br>

    <label>Qty Beli:</label>
    <input type="number" name="qty_beli" id="qty_beli" required><br>

    <label>Pack:</label>
    <input type="text" name="pack" id="pack"><br>

    <label>Harga:</label>
    <input type="number" name="harga" id="harga" step="0.01" required><br>

    <label>Metode Pembayaran:</label>
    <select name="metode_pembayaran" id="metode_pembayaran" required>
        <?php foreach ($metode_pembayaran as $mp): ?>
            <option value="<?= $mp['id'] ?>"><?= $mp['nama_metode'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Simpan</button>
</form>

<form method="GET" action="<?= base_url('perbelanjaan/index') ?>">
    <label>Filter Bulan:</label>
    <select name="month">
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $selected_month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
            </option>
        <?php endfor; ?>
    </select>
    <button type="submit">Filter</button>
</form>

    <h2>Data Belanja (Bulan: <?= date('F', mktime(0, 0, 0, $selected_month, 10)) ?>)</h2>
    <table border="1">
    <tr>
        <th>No</th>
        <th>ID Belanja</th>
        <th>Tanggal</th>
        <th>Jenis Pengeluaran</th>
        <th>Nama Barang</th>
        <th>Nama Bahan Baku</th>
        <th>Tipe</th>
        <th>Merk</th>
        <th>Ukuran</th>
        <th>Unit</th>
        <th>Qty Beli</th>
        <th>Pack</th>
        <th>Total Unit</th>
        <th>Harga</th>
        <th>Total Harga</th>
        <th>Metode Pembayaran</th>
        <th>Aksi</th>
    </tr>
    <?php 
    $no = 1; 
    foreach ($perbelanjaan as $pb): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $pb['id_id_belanja'] ?></td>
        <td><?= $pb['tanggal'] ?></td>
        <td><?= $pb['nama_pengeluaran'] ?></td>
        <td><?= $pb['nama_barang'] ?></td>
        <td><?= $pb['nama_bahan_baku'] ?></td>
        <td><?= $pb['tipe'] ?></td>
        <td><?= $pb['merk'] ?></td>
        <td><?= $pb['ukuran'] ?></td>
        <td><?= $pb['unit'] ?></td>
        <td><?= $pb['qty_beli'] ?></td>
        <td><?= $pb['pack'] ?></td>
        <td><?= $pb['total_unit'] ?></td>
        <td><?= number_format($pb['harga'], 2, ',', '.') ?></td>
        <td><?= number_format($pb['total_harga'], 2, ',', '.') ?></td>
        <td><?= $pb['nama_metode'] ?></td>
        <td>
            <a href="<?= base_url('perbelanjaan/edit/' . $pb['id']) ?>">Edit</a> |
            <a href="<?= base_url('perbelanjaan/delete/' . $pb['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>
        <?php endforeach; ?>
    </table>

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-belanja');
    const resultsList = document.getElementById('search-results');
    const tableBody = document.querySelector('#belanja-table tbody');
    let rowCount = 0;

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length > 1) {
            fetch(`<?= base_url('belanja/search_belanja') ?>?search=${query}`)
                .then(response => response.json())
                .then(data => {
                    resultsList.innerHTML = '';
                    resultsList.style.display = 'block';

                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            ${item.label} 
                            <button type="button" class="add-btn" data-id="${item.id}">Tambah</button>
                        `;
                        li.style.cursor = 'pointer';
                        li.style.padding = '5px';
                        resultsList.appendChild(li);

                        // Event listener for "Tambah" button
                        li.querySelector('.add-btn').addEventListener('click', function () {
                            const id = this.getAttribute('data-id');

                            fetch(`<?= base_url('belanja/get_belanja_by_id/') ?>${id}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.error) {
                                        alert(data.error);
                                    } else {
                                        rowCount++;
                                        const totalUnit = data.ukuran * data.qty_beli;
                                        const totalHarga = data.harga * data.qty_beli;

                                        const row = document.createElement('tr');
                                        row.innerHTML = `
                                            <td>${rowCount}</td>
                                            <td>${data.id}</td>
                                            <td>${data.tanggal}</td>
                                            <td>${data.nama_pengeluaran}</td>
                                            <td>${data.nama_barang}</td>
                                            <td>${data.nama_bahan_baku}</td>
                                            <td>${data.tipe}</td>
                                            <td>${data.merk}</td>
                                            <td>${data.ukuran}</td>
                                            <td>${data.unit}</td>
                                            <td>
                                                <input type="number" class="qty-input" value="1" min="1">
                                            </td>
                                            <td>${data.pack}</td>
                                            <td class="total-unit">${totalUnit}</td>
                                            <td>${data.harga}</td>
                                            <td class="total-harga">${totalHarga}</td>
                                            <td>${data.nama_metode}</td>
                                            <td>
                                                <button type="button" class="delete-btn">Hapus</button>
                                            </td>
                                        `;

                                        // Event listener for Qty change
                                        row.querySelector('.qty-input').addEventListener('input', function () {
                                            const qty = this.value;
                                            row.querySelector('.total-unit').textContent = data.ukuran * qty;
                                            row.querySelector('.total-harga').textContent = data.harga * qty;
                                        });

                                        // Event listener for delete
                                        row.querySelector('.delete-btn').addEventListener('click', function () {
                                            row.remove();
                                            rowCount--;
                                        });

                                        tableBody.appendChild(row);
                                        resultsList.style.display = 'none';
                                        searchInput.value = ''; // Reset input pencarian
                                    }
                                });
                        });
                    });
                })
                .catch(err => console.error('Error:', err));
        } else {
            resultsList.style.display = 'none';
        }
    });

    // Klik di luar pencarian untuk menutup dropdown
    document.addEventListener('click', function (e) {
        if (!resultsList.contains(e.target) && e.target !== searchInput) {
            resultsList.style.display = 'none';
        }
    });
});
</script>



</html>
