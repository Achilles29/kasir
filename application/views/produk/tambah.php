<div class="container">
    <h2 class="mb-3"><?= $title ?></h2>
    <form action="<?= isset($produk) ? site_url('produk/edit/'.$produk['id']) : site_url('produk/tambah'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_produk">Nama Produk*</label>
            <input type="text" class="form-control" name="nama_produk" value="<?= isset($produk) ? $produk['nama_produk'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="sku">SKU*</label>
            <input type="text" class="form-control" name="sku" value="<?= isset($produk) ? $produk['sku'] : ''; ?>" required>
        </div>        
        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea class="form-control" name="deskripsi"> <?= isset($produk) ? $produk['deskripsi'] : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="satuan">Satuan*</label>
            <input type="text" class="form-control" name="satuan" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto Produk</label>
            <input type="file" class="form-control" name="foto">
        </div>
        
        <div class="form-group">
            <label for="kategori_id">Kategori Produk*</label>
            <select class="form-control" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id']; ?>">
                        <?= htmlspecialchars($k['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="harga_jual">Harga Jual*</label>
            <input type="number" class="form-control" step="0.01" name="harga_jual" value="<?= isset($produk) ? $produk['harga_jual'] : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="monitor_persediaan">Monitor Persediaan</label>
            <select class="form-control" name="monitor_persediaan">
                <option value="1" <?= isset($produk) && $produk['monitor_persediaan'] == 1 ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= isset($produk) && $produk['monitor_persediaan'] == 2 ? 'selected' : ''; ?>>Tidak</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="tampil">Tampilkan di Menu</label>
            <select class="form-control" name="tampil">
                <option value="1" <?= isset($produk) && $produk['tampil'] == 1 ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= isset($produk) && $produk['tampil'] == 2 ? 'selected' : ''; ?>>Tidak</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>