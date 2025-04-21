<div class="container">
    <h2>POS - Transaksi</h2>
    <form action="<?= site_url('transaksi/tambah'); ?>" method="post">
        <input type="text" name="pelanggan" placeholder="Nama Pelanggan" required>
        <input type="number" name="total" placeholder="Total Harga" required>
        <button type="submit">Simpan Transaksi</button>
    </form>
</div>
