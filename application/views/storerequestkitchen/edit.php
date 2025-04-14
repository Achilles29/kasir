<div class="container">
    <h2 class="mt-4"><?= $title ?></h2>

    <!-- Form Edit -->
    <form method="post" action="<?= base_url('storerequestkitchen/update') ?>">
        <input type="hidden" name="id" value="<?= $request['id'] ?>">

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" 
                value="<?= $request['tanggal'] ?? date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" id="nama_barang" class="form-control" 
                value="<?= $request['nama_barang'] ?? 'Tidak tersedia' ?>" readonly>
        </div>

        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" id="merk" class="form-control" 
                value="<?= $request['merk'] ?? 'Tidak tersedia' ?>" readonly>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" class="form-control" readonly><?= $request['keterangan'] ?? 'Tidak tersedia' ?></textarea>
        </div>

        <div class="form-group">
            <label for="sisa_stok">Sisa Stok:</label>
            <input type="number" id="sisa_stok" name="sisa_stok" class="form-control" value="<?= $sisa_stok ?>" readonly>
        </div>

        <div class="form-group">
            <label for="kuantitas">Kuantitas:</label>
            <input type="number" id="kuantitas" name="kuantitas" class="form-control" value="<?= $request['kuantitas'] ?>" required>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea id="catatan" name="catatan" class="form-control"><?= $request['catatan'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="verified" <?= $request['status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                <option value="rejected" <?= $request['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('storerequestkitchen') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.getElementById('kuantitas').addEventListener('input', function () {
    const sisaStok = parseInt(document.getElementById('sisa_stok').value, 10);
    const kuantitas = parseInt(this.value, 10);

    if (kuantitas > sisaStok) {
        alert('Kuantitas melebihi sisa stok!');
        this.value = ''; // Kosongkan input jika melebihi stok
    }
});


</script>
