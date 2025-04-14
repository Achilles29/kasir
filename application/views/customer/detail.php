<div class="container mt-4">
    <div class="card p-4">
        <h4 class="mb-4">Detail Pelanggan</h4>

        <div class="text-center mb-3">
        <img src="<?= base_url('uploads/foto_pelanggan/'.$customer['foto']); ?>" class="img-thumbnail" width="150">
        <!-- <img src="<?= base_url('uploads/customer/' . ($customer['foto'] ?? 'default.png')) ?>" alt="Foto Pelanggan" class="img-thumbnail" style="width:150px; height:150px; object-fit:cover;"> -->
            <p class="text-muted mt-2"><?= $customer['nama'] ?></p>
        </div>

        <table class="table table-bordered">
            <tr><th>Kode Pelanggan</th><td><?= $customer['kode_pelanggan'] ?></td></tr>
            <tr><th>Nama</th><td><?= $customer['nama'] ?></td></tr>
            <tr><th>Tanggal Lahir</th>
                <td>
                    <?= date("d F Y", strtotime($customer['tanggal_lahir'])) ?> 
                    (Usia: <?= date_diff(date_create($customer['tanggal_lahir']), date_create('today'))->y ?> Tahun)
                </td>
            </tr>
            <tr><th>Jenis Kelamin</th><td><?= $customer['jenis_kelamin'] ?></td></tr>
            <tr><th>Alamat</th><td><?= $customer['alamat'] ?></td></tr>
            <tr><th>Nomor Telepon</th><td><?= $customer['telepon'] ?></td></tr>
            <tr><th>Email</th><td><?= $customer['email'] ?? '-' ?></td></tr>
        </table>

        <a href="<?= site_url('customer') ?>" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>

