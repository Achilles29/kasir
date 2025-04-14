<div class="container-fluid">
    <h2>Edit Purchase</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('purchase/update/' . $purchase['id']) ?>">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control form-control-sm" 
                        value="<?= isset($purchase['nama_barang']) ? $purchase['nama_barang'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_bahan_baku">Nama Bahan Baku</label>
                    <input type="text" id="nama_bahan_baku" name="nama_bahan_baku" class="form-control form-control-sm" value="<?= $purchase['nama_bahan_baku'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" class="form-control">
                        <option value="">Pilih Kategori</option>
                        <?php if (!empty($kategori_list)): ?>
                            <?php foreach ($kategori_list as $kategori): ?>
                                <option value="<?= $kategori['id'] ?>" <?= $purchase['id_kategori'] == $kategori['id'] ? 'selected' : '' ?>>
                                    <?= $kategori['nama_kategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Data kategori tidak tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipe_produksi">Tipe Produksi</label>
                    <select id="tipe_produksi" name="tipe_produksi" class="form-control">
                        <option value="">Pilih Tipe Produksi</option>
                        <?php if (!empty($tipe_produksi_list)): ?>
                            <?php foreach ($tipe_produksi_list as $tipe): ?>
                                <option value="<?= $tipe['id'] ?>" <?= $purchase['id_tipe_produksi'] == $tipe['id'] ? 'selected' : '' ?>>
                                    <?= $tipe['nama_tipe_produksi'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Data tipe produksi tidak tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input type="text" id="merk" name="merk" class="form-control form-control-sm" value="<?= $purchase['merk'] ?>">
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" class="form-control form-control-sm"><?= $purchase['keterangan'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ukuran">Ukuran</label>
                    <input type="number" id="ukuran" name="ukuran" class="form-control form-control-sm" value="<?= $purchase['ukuran'] ?>" step="0.01">
                </div>
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <input type="text" id="unit" name="unit" class="form-control form-control-sm" value="<?= $purchase['unit'] ?>">
                </div>
                <div class="form-group">
                    <label for="pack">Pack</label>
                    <input type="text" id="pack" name="pack" class="form-control form-control-sm" value="<?= $purchase['pack'] ?>">
                </div>
                <div class="form-group">
                    <label for="harga_satuan">Harga Satuan</label>
                    <input type="number" id="harga_satuan" name="harga_satuan" class="form-control form-control-sm" value="<?= $purchase['harga_satuan'] ?>" step="0.01">
                </div>
                <div class="form-group">
                    <label for="kuantitas">Kuantitas</label>
                    <input type="number" id="kuantitas" name="kuantitas" class="form-control form-control-sm" value="<?= $purchase['kuantitas'] ?>" min="1" step="1">
                </div>
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm">
                        <?php foreach ($metode_pembayaran as $metode): ?>
                            <option value="<?= $metode['id'] ?>" <?= $metode['id'] == $purchase['metode_pembayaran'] ? 'selected' : '' ?>>
                                <?= $metode['nama_rekening'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                    <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control form-control-sm">
                        <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                            <option value="<?= $jenis['id'] ?>" <?= $jenis['id'] == $purchase['jenis_pengeluaran'] ? 'selected' : '' ?>>
                                <?= $jenis['nama_jenis_pengeluaran'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>
