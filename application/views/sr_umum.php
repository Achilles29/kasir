
<style>
    /* Pagination Styling */
.pagination {
    margin-top: 20px;
}

.pagination .page-item {
    display: inline-block;
    margin: 0 2px;
}

.pagination .page-item a, 
.pagination .page-item span {
    padding: 8px 15px;
    color: #007bff;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.pagination .page-item.active a,
.pagination .page-item.active span {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination .page-item:hover a,
.pagination .page-item:hover span {
    background-color: #f0f0f0;
}

</style>
<div class="container-fluid">
    <h2><?= $title ?></h2>
    <!-- Form Input Store Request -->

    <!-- Tabel Store Request -->
<div class="table-responsive">
        <table class="table table-bordered table-striped">    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Pengeluaran</th>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Keterangan</th>
            <th>Ukuran</th>
            <th>Unit</th>
            <th>Harga Satuan</th>
            <th>Kuantitas</th>
            <th>Total Unit</th>
            <th>Total Harga</th>
            <th>HPP</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = + 1; // Nomor urut dimulai dari offset + 1
        foreach ($store_request as $item): ?>
            <tr>
                <td><?= $no++ ?></td> <!-- Nomor urut -->
                <td><?= $item['tanggal'] ?></td>
                <td><?= $item['nama_jenis_pengeluaran'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['merk'] ?></td>
                <td><?= $item['keterangan'] ?></td>
                <td class="text-right"><?= $item['ukuran'] ?></td>
                <td><?= $item['unit'] ?></td>
                <td class="text-right"><?= number_format($item['harga'], 2) ?></td>
                <td class="text-right"><?= $item['kuantitas'] ?></td>
                <td class="text-right"><?= $item['kuantitas'] * $item['ukuran'] ?></td>
                <td class="text-right"><?= number_format($item['kuantitas'] * $item['harga'], 2) ?></td>
                <td class="text-right"><?= number_format($item['harga'] / $item['ukuran'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
