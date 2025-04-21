<div class="container-fluid">
    <h2><?= $title ?></h2>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('gudangawal/update') ?>">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">

        <div class="form-group">
            <label for="bl_db_belanja_id">ID Belanja</label>
            <input type="text" class="form-control" id="bl_db_belanja_id" name="bl_db_belanja_id" 
                   value="<?= $item['bl_db_belanja_id'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="bl_db_purchase_id">ID Purchase</label>
            <input type="text" class="form-control" id="bl_db_purchase_id" name="bl_db_purchase_id" 
                   value="<?= $item['bl_db_purchase_id'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                   value="<?= $belanja['nama_barang'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="nama_bahan_baku">Nama Bahan Baku</label>
            <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku" 
                   value="<?= $belanja['nama_bahan_baku'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" class="form-control" id="merk" name="merk" 
                   value="<?= $purchase['merk'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="ukuran">Ukuran</label>
            <input type="text" class="form-control" id="ukuran" name="ukuran" 
                   value="<?= $purchase['ukuran'] ?>" readonly>
        </div>

        <div class="form-group">
            <label for="harga_satuan">Harga Satuan</label>
            <input type="text" class="form-control" id="harga_satuan" name="harga_satuan" 
                   value="<?= number_format($purchase['harga_satuan'], 2) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="kuantitas">Kuantitas</label>
            <input type="number" class="form-control" id="kuantitas" name="kuantitas" 
                   value="<?= $item['kuantitas'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('gudangawal') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
