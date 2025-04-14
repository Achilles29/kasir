<div class="container">
    <h2><?= isset($produk) ? 'Edit Produk' : 'Tambah Produk'; ?></h2>
    <form action="<?= isset($produk) ? site_url('produk/edit/'.$produk['id']) : site_url('produk/tambah'); ?>" method="post" enctype="multipart/form-data">
        <label for="nama_produk">Nama Produk*</label>
        <input type="text" name="nama_produk" value="<?= isset($produk) ? $produk['nama_produk'] : ''; ?>" required>
        
        <label for="deskripsi">Deskripsi Produk</label>
        <textarea name="deskripsi"> <?= isset($produk) ? $produk['deskripsi'] : ''; ?></textarea>
        
        <label for="foto">Foto Produk</label>
        <input type="file" name="foto">
        
        <label for="kategori_id">Kategori Produk*</label>
        <select name="kategori_id">
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id']; ?>" <?= isset($produk) && $produk['kategori_id'] == $k['id'] ? 'selected' : ''; ?>>
                    <?= $k['nama_kategori']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label for="harga_jual">Harga Jual*</label>
        <input type="number" step="0.01" name="harga_jual" value="<?= isset($produk) ? $produk['harga_jual'] : ''; ?>" required>
        
        <label for="monitor_persediaan">Monitor Persediaan</label>
        <input type="checkbox" name="monitor_persediaan" <?= isset($produk) && $produk['monitor_persediaan'] ? 'checked' : ''; ?>>
        
        <label for="tampil_menu">Tampil di Menu</label>
        <input type="checkbox" name="tampil_menu" <?= isset($produk) && $produk['tampil_menu'] ? 'checked' : ''; ?>>
        
        <button type="submit">Simpan</button>
    </form>
</div>