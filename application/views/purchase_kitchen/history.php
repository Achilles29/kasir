<div class="container-fluid">
    <h2>Riwayat Purchase Order Bar</h2>

    <form method="GET" action="<?= base_url('purchase_kitchen/history') ?>" class="form-inline mb-3">
        <label for="tanggal_awal" class="mr-2">Tanggal Awal</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control mr-3" value="<?= $tanggal_awal ?>">

        <label for="tanggal_akhir" class="mr-2">Tanggal Akhir</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control mr-3" value="<?= $tanggal_akhir ?>">

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['tanggal'] ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['status'] ?></td>
                    <td><?= number_format($item['total_harga'], 2) ?></td>
                    <td>
                        <a href="<?= base_url('purchase_kitchen/detail/' . $item['id']) ?>" class="btn btn-info btn-sm">Detail</a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
