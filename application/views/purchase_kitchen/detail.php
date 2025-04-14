<div class="container-fluid">
    <h2>Detail Purchase Order</h2>

    <div class="card">
        <div class="card-header">
            <strong>Purchase ID:</strong> <?= $purchase['id'] ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal Pembelian</th>
                    <td><?= $purchase['tanggal'] ?></td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td><?= $purchase['nama_barang'] ?></td>
                </tr>
                <tr>
                    <th>Nama Bahan Baku</th>
                    <td><?= $purchase['nama_bahan_baku'] ?></td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td><?= $purchase['kategori'] ?></td>
                </tr>
                <tr>
                    <th>Tipe Produksi</th>
                    <td><?= $purchase['tipe_produksi'] ?></td>
                </tr>
                <tr>
                    <th>Jenis Pengeluaran</th>
                    <td><?= $purchase['jenis_pengeluaran'] ?></td>
                </tr>
                <tr>
                    <th>Merk</th>
                    <td><?= $purchase['merk'] ?></td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td><?= $purchase['keterangan'] ?></td>
                </tr>
                <tr>
                    <th>Ukuran</th>
                    <td><?= $purchase['ukuran'] ?></td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td><?= $purchase['unit'] ?></td>
                </tr>
                <tr>
                    <th>Pack</th>
                    <td><?= $purchase['pack'] ?></td>
                </tr>
                <tr>
                    <th>Harga Satuan</th>
                    <td><?= number_format($purchase['harga_satuan'], 2) ?></td>
                </tr>
                <tr>
                    <th>Kuantitas</th>
                    <td><?= $purchase['kuantitas'] ?></td>
                </tr>
                <tr>
                    <th>Total Unit</th>
                    <td><?= $purchase['total_unit'] ?></td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td><?= number_format($purchase['total_harga'], 2) ?></td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td><?= $purchase['metode_pembayaran'] ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= ucfirst($purchase['status']) ?></td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td><?= $purchase['catatan'] ?></td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('purchase_kitchen/history') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
