<div class="container-fluid">
    <h2>Edit Purchase Order Bar</h2>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form class="mb-3" method="post" action="<?= base_url('purchase_bar/update/' . $purchase['id']) ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control form-control-sm" value="<?= $purchase['tanggal'] ?>">
            </div>
            <div class="col-md-4">
                <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control form-control-sm" required>
                    <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                        <option value="<?= $jenis['id'] ?>" <?= ($jenis['id'] == $purchase['jenis_pengeluaran']) ? 'selected' : '' ?>>
                            <?= $jenis['nama_jenis_pengeluaran'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" class="form-control form-control-sm" value="<?= $purchase['nama_barang'] ?>">
            </div>
            <div class="col-md-4">
                <label for="nama_bahan_baku">Nama Bahan Baku</label>
                <input type="text" id="nama_bahan_baku" name="nama_bahan_baku" class="form-control form-control-sm" value="<?= $purchase['nama_bahan_baku'] ?>">
            </div>
            <div class="col-md-4">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-control form-control-sm">
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>" <?= ($kategori['id'] == $purchase['kategori_id']) ? 'selected' : '' ?>>
                            <?= $kategori['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tipe_produksi">Tipe Produksi</label>
                <select id="tipe_produksi" name="tipe_produksi" class="form-control form-control-sm">
                    <?php foreach ($tipe_produksi_list as $tipe): ?>
                        <option value="<?= $tipe['id'] ?>" <?= ($tipe['id'] == $purchase['tipe_produksi_id']) ? 'selected' : '' ?>>
                            <?= $tipe['nama_tipe_produksi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" class="form-control form-control-sm" value="<?= $purchase['merk'] ?>">
            </div>
            <div class="col-md-4">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="form-control form-control-sm"><?= $purchase['keterangan'] ?></textarea>
            </div>
            <div class="col-md-2">
                <label for="ukuran">Ukuran</label>
                <input type="text" id="ukuran" name="ukuran" class="form-control form-control-sm" value="<?= $purchase['ukuran'] ?>">
            </div>
            <div class="col-md-2">
                <label for="unit">Unit</label>
                <input type="text" id="unit" name="unit" class="form-control form-control-sm" value="<?= $purchase['unit'] ?>">
            </div>
            <div class="col-md-2">
                <label for="pack">Pack</label>
                <input type="text" id="pack" name="pack" class="form-control form-control-sm" value="<?= $purchase['pack'] ?>">
            </div>
            <div class="col-md-4">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="number" id="harga_satuan" name="harga_satuan" class="form-control form-control-sm" value="<?= $purchase['harga_satuan'] ?>">
            </div>
            <div class="col-md-2">
                <label for="kuantitas">Kuantitas</label>
                <input type="number" id="kuantitas" name="kuantitas" class="form-control form-control-sm" value="<?= $purchase['kuantitas'] ?>">
            </div>
            <div class="col-md-4">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm" required>
                    <?php foreach ($metode_pembayaran as $metode): ?>
                        <option value="<?= $metode['id'] ?>" <?= ($metode['id'] == $purchase['metode_pembayaran']) ? 'selected' : '' ?>>
                            <?= $metode['nama_rekening'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control form-control-sm"><?= $purchase['catatan'] ?></textarea>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </div>
    </form>
</div>
