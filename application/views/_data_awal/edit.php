<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Awal</title>
</head>
<body>
    <h1>Edit Data Awal</h1>
    <form action="<?= base_url('data_awal/edit/' . $data_awal['id']) ?>" method="POST">
        <label>Nama Barang:</label>
        <input type="text" value="<?= $data_awal['nama_barang'] ?>" readonly><br>

        <label>Stok Awal:</label>
        <input type="number" name="stok_awal" value="<?= $data_awal['stok_awal'] ?>" required><br>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?= $data_awal['tanggal'] ?>" required><br>

        <button type="submit">Simpan</button>
        <a href="<?= base_url('data_awal') ?>">Batal</a>
    </form>
</body>
</html>
