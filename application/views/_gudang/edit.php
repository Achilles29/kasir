<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Gudang</title>
</head>
<body>
    <h1>Edit Data Gudang</h1>
    <form method="post" action="<?php echo site_url('gudang/update/' . $item['id']); ?>">
        <label>Stok Awal:</label>
        <input type="number" name="stok_awal" value="<?php echo $item['stok_awal']; ?>"><br>
        <label>Stok Keluar:</label>
        <input type="number" name="stok_keluar" value="<?php echo $item['stok_keluar']; ?>"><br>
        <label>Stok Terbuang:</label>
        <input type="number" name="stok_terbuang" value="<?php echo $item['stok_terbuang']; ?>"><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
