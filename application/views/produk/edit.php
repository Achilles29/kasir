<div class="container">
    <h2>Edit Produk</h2>
    <form action="<?= site_url('produk/edit/'.$produk['id']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_produk">Nama Produk*</label>
            <input type="text" class="form-control" name="nama_produk" value="<?= $produk['nama_produk']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="sku">SKU*</label>
            <input type="text" class="form-control" name="sku" value="<?= $produk['sku']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea class="form-control" name="deskripsi"> <?= $produk['deskripsi']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="satuan">Satuan*</label>
            <input type="text" class="form-control" name="satuan" value="<?= $produk['satuan']; ?>" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto Produk</label>
            <input type="file" class="form-control" name="foto">
            <?php if (!empty($produk['foto'])): ?>
                <img src="<?= base_url('uploads/produk/'.$produk['foto']); ?>" width="100" alt="Foto Produk">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="kategori_id">Kategori Produk*</label>
            <select class="form-control" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id']; ?>" <?= isset($produk['kategori_id']) && $produk['kategori_id'] == $k['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($k['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="harga_jual">Harga Jual*</label>
            <input type="number" class="form-control" step="0.01" name="harga_jual" value="<?= $produk['harga_jual']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="monitor_persediaan">Monitor Persediaan</label>
            <select class="form-control" name="monitor_persediaan">
                <option value="1" <?= $produk['monitor_persediaan'] == 1 ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= $produk['monitor_persediaan'] == 2 ? 'selected' : ''; ?>>Tidak</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="tampil">Tampilkan di Menu</label>
            <select class="form-control" name="tampil">
                <option value="1" <?= $produk['tampil'] == 1 ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= $produk['tampil'] == 2 ? 'selected' : ''; ?>>Tidak</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>