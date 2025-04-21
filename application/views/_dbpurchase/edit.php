<div class="container-fluid">
    <h2><?= $title ?></h2>
    <form method="post" action="<?= base_url('dbpurchase/update') ?>">
        <input type="hidden" name="id" value="<?= $purchase['id'] ?>">
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= $purchase['nama_barang'] ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_bahan_baku">Nama Bahan Baku</label>
            <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku" value="<?= $purchase['nama_bahan_baku'] ?>" required>
        </div>
        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select class="form-control" id="id_kategori" name="id_kategori">
                <option value="">Pilih Kategori</option>
                <?php foreach ($categories as $kategori): ?>
                    <option value="<?= $kategori->id ?>" <?= $kategori->id == $purchase['id_kategori'] ? 'selected' : '' ?>>
                        <?= $kategori->nama_kategori ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_tipe_produksi">Tipe Produksi</label>
            <select class="form-control" id="id_tipe_produksi" name="id_tipe_produksi">
                <option value="">Pilih Tipe Produksi</option>
                <?php foreach ($production_types as $tipe): ?>
                    <option value="<?= $tipe->id ?>" <?= $tipe->id == $purchase['id_tipe_produksi'] ? 'selected' : '' ?>>
                        <?= $tipe->nama_tipe_produksi ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" class="form-control" id="merk" name="merk" value="<?= $purchase['merk'] ?>">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan"><?= $purchase['keterangan'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="ukuran">Ukuran</label>
            <input type="number" class="form-control" id="ukuran" name="ukuran" value="<?= $purchase['ukuran'] ?>">
        </div>
        <div class="form-group">
            <label for="unit">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" value="<?= $purchase['unit'] ?>">
        </div>
        <div class="form-group">
            <label for="pack">Pack</label>
            <input type="text" class="form-control" id="pack" name="pack" value="<?= $purchase['pack'] ?>">
        </div>
        <div class="form-group">
            <label for="harga_satuan">Harga Satuan</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" value="<?= $purchase['harga_satuan'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
