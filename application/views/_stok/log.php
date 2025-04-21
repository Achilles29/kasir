<div class="container mt-4">
  <h4><?= $title ?></h4>
  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>Tanggal</th>
          <th>Divisi</th>
          <th>Nama Bahan</th>
          <th>Jenis</th>
          <th>Jumlah</th>
          <th>hpp</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($log as $row): ?>
          <tr>
            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
            <td><?= $row['nama_divisi'] ?></td>
            <td><?= $row['nama_barang'] ?></td>
            <td><?= $row['jenis_transaksi'] ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= $row['hpp'] ?></td>
            <td><?= $row['keterangan'] ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
