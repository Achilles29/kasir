<div class="container-fluid">
    <h2><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <a href="<?= base_url('dailyinventory/sync_storeroom') ?>" class="btn btn-primary mb-3">Sinkronkan Data Storeroom</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Pembelian</th>
            <th>Nama Barang</th>
            <th>Jenis Pengeluaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($daily_inventory as $item): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($item['tanggal_pembelian'])) ?></td>
            <td><?= $item['nama_barang'] ?></td>
            <td><?= $item['jenis_pengeluaran'] ?></td>
            <td>
                <a href="<?= base_url('dailyinventory/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('dailyinventory/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <div class="row">
        <div class="col-md-12">
            <?= $pagination ?>
        </div>
    </div>
</div>
