<div class="container mt-4">
    <h2>Kasir POS</h2>
    <div class="row">
        <div class="col-md-8">
            <input type="text" id="search" class="form-control" placeholder="Cari Produk...">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="produk-list">
                    <?php foreach ($produk as $p): ?>
                        <tr>
                            <td><?= $p['nama_produk']; ?></td>
                            <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.'); ?></td>
                            <td><button class="btn btn-success add-to-cart" data-id="<?= $p['id']; ?>">Tambah</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4>Keranjang</h4>
            <ul id="cart-list"></ul>
            <button class="btn btn-primary w-100">Bayar</button>
        </div>
    </div>
</div>
