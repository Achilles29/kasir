<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Perbelanjaan</title>
</head>
<body>
    <h1>Tambah Data Perbelanjaan</h1>

    <form method="post" action="<?php echo site_url('perbelanjaan/save'); ?>">
        <label>Tanggal:</label>
        <input type="date" name="tanggal" required><br>

        <label>Jenis Pengeluaran:</label>
        <select name="jenis_pengeluaran" required>
            <?php foreach ($jenis_pengeluaran as $jp): ?>
                <option value="<?php echo $jp['id']; ?>"><?php echo $jp['nama_jenis_pengeluaran']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Nama Barang:</label>
        <select name="nama_barang" required>
            <?php foreach ($db_belanja as $db): ?>
                <option value="<?php echo $db['nama_barang']; ?>"><?php echo $db['nama_barang']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <!-- Tambahkan input untuk kolom lainnya -->
        
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
