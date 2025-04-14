<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Belanja</title>
</head>
<body>
    <h1>Edit Data Belanja</h1>

    <form action="" method="POST">
        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?= $belanja['tanggal'] ?>" required><br>

        <label>Jenis Pengeluaran:</label>
        <select name="jenis_pengeluaran" required>
            <?php foreach ($jenis_pengeluaran as $jp): ?>
                <option value="<?= $jp['id'] ?>" <?= $belanja['jenis_pengeluaran'] == $jp['id'] ? 'selected' : '' ?>>
                    <?= $jp['nama_pengeluaran'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Nama Barang:</label>
        <input type="text" name="nama_barang" value="<?= $belanja['nama_barang'] ?>" required><br>

        <label>Nama Bahan Baku:</label>
        <input type="text" name="nama_bahan_baku" value="<?= $belanja['nama_bahan_baku'] ?>"><br>

        <label>Tipe:</label>
        <input type="text" name="tipe" value="<?= $belanja['tipe'] ?>"><br>

        <label>Merk:</label>
        <input type="text" name="merk" value="<?= $belanja['merk'] ?>"><br>

        <label>Ukuran:</label>
        <input type="number" name="ukuran" value="<?= $belanja['ukuran'] ?>" step="0.01" required><br>

        <label>Unit:</label>
        <input type="text" name="unit" value="<?= $belanja['unit'] ?>"><br>

        <label>Qty Beli:</label>
        <input type="number" name="qty_beli" value="<?= $belanja['qty_beli'] ?>" required><br>

        <label>Pack:</label>
        <input type="text" name="pack" value="<?= $belanja['pack'] ?>"><br>

        <label>Harga:</label>
        <input type="number" name="harga" value="<?= $belanja['harga'] ?>" step="0.01" required><br>

        <label>Metode Pembayaran:</label>
        <select name="metode_pembayaran" required>
            <?php foreach ($metode_pembayaran as $mp): ?>
                <option value="<?= $mp['id'] ?>" <?= $belanja['metode_pembayaran'] == $mp['id'] ? 'selected' : '' ?>>
                    <?= $mp['nama_metode'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
