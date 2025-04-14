<h2><?= $title ?></h2>
<form action="" method="post">
    <label for="tanggal_pembelian">Tanggal Pembelian:</label>
    <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" required>
    <br>

    <label for="jenis_pengeluaran">Jenis Pengeluaran:</label>
    <select name="jenis_pengeluaran" id="jenis_pengeluaran" required>
        <option value="">Pilih Jenis</option>
        <?php foreach ($this->db->get('bl_jenis_pengeluaran')->result() as $jenis): ?>
        <option value="<?= $jenis->id ?>"><?= $jenis->nama_jenis_pengeluaran ?></option>
        <?php endforeach; ?>
    </select>
    <br>

    <label for="bl_db_belanja_id">Barang:</label>
    <select name="bl_db_belanja_id" id="bl_db_belanja_id" required>
        <option value="">Pilih Barang</option>
        <?php foreach ($this->db->get('bl_db_belanja')->result() as $barang): ?>
        <option value="<?= $barang->id ?>"><?= $barang->nama_barang ?></option>
        <?php endforeach; ?>
    </select>
    <br>

    <label for="kuantitas">Kuantitas:</label>
    <input type="number" name="kuantitas" id="kuantitas" required>
    <br>

    <label for="total_harga">Total Harga:</label>
    <input type="number" name="total_harga" id="total_harga" step="0.01" required>
    <br>

    <label for="metode_pembayaran">Metode Pembayaran:</label>
    <select name="metode_pembayaran" id="metode_pembayaran" required>
        <option value="">Pilih Metode</option>
        <?php foreach ($this->db->get('bl_rekening')->result() as $rekening): ?>
        <option value="<?= $rekening->id ?>"><?= $rekening->nama_rekening ?></option>
        <?php endforeach; ?>
    </select>
    <br>

    <button type="submit">Simpan</button>
</form>
