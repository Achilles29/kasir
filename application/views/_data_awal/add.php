<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Awal</title>
</head>
<body>
    <h1>Tambah Data Awal</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div style="color: red;">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('data_awal/save') ?>" method="POST">
        <label>ID Barang:</label>
        <select name="id_belanja" required>
            <option value="">Pilih Barang</option>
            <?php foreach ($db_belanja as $barang): ?>
                <option value="<?= $barang['id'] ?>"><?= $barang['nama_barang'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Stok Awal:</label>
        <input type="number" name="stok_awal" required><br>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required><br>

        <button type="submit">Simpan</button>
        <a href="<?= base_url('data_awal') ?>">Batal</a>
    </form>
</body>
</html>
