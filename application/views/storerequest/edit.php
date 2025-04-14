<div class="container">
    <h2><?= $title ?></h2>
    <form method="post" action="<?= base_url('storerequest/edit/' . $store_request['id']) ?>" id="edit-form">
        <div class="form-row">
            <div class="col-md-6">
                <label>Jenis Pengeluaran</label>
                <select name="jenis_pengeluaran" class="form-control">
                    <?php foreach ($jenis_pengeluaran as $jenis): ?>
                        <option value="<?= $jenis['id'] ?>" <?= $jenis['id'] == $store_request['jenis_pengeluaran'] ? 'selected' : '' ?>>
                            <?= $jenis['nama_jenis_pengeluaran'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col-md-6">
                <label>Nama Barang</label>
                <input type="text" class="form-control" value="<?= $store_request['nama_barang'] ?>" readonly>
            </div>
            <div class="col-md-6">
                <label>Merk</label>
                <input type="text" class="form-control" value="<?= $store_request['merk'] ?>" readonly>
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col-md-6">
                <label>Keterangan</label>
                <input type="text" class="form-control" value="<?= $store_request['keterangan'] ?>" readonly>
            </div>
            <div class="col-md-3">
                <label>Ukuran - Unit</label>
                <input type="text" class="form-control" value="<?= $store_request['ukuran'] . ' - ' . $store_request['unit'] ?>" readonly>
            </div>
            <div class="col-md-3">
                <label>Harga Satuan</label>
                <input type="text" class="form-control" value="<?= number_format($store_request['harga'], 2) ?>" readonly>
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col-md-3">
                <label>Stok Akhir</label>
                <input type="number" id="stok_akhir" class="form-control" value="<?= $stok_akhir ?>" readonly>
            </div>
            <div class="col-md-3">
                <label>Kuantitas</label>
                <input type="number" name="kuantitas" id="kuantitas" class="form-control" value="<?= $store_request['kuantitas'] ?>" required>
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('storerequest') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('kuantitas').addEventListener('input', function () {
        const kuantitas = parseFloat(this.value);
        const stokAkhir = parseFloat(document.getElementById('stok_akhir').value);

        if (kuantitas > stokAkhir) {
            alert('Kuantitas melebihi stok akhir!');
            this.value = stokAkhir; // Batasi kuantitas agar tidak melebihi stok akhir
        }
    });
</script>