<div class="container">
  <h4 class="mb-4"><?= $title ?></h4>
  <form action="<?= base_url('stok/simpan') ?>" method="post">
    <div class="row mb-3">
      <div class="col-md-6">
        <label>Nama Bahan</label>
        <select name="bahan_id" class="form-select" required>
          <option value="">Pilih Bahan</option>
          <?php foreach ($bahan as $b): ?>
            <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>Divisi</label>
        <select name="divisi_id" class="form-select" required>
          <option value="">Pilih Divisi</option>
          <?php foreach ($divisi as $d): ?>
            <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-4">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="col-md-4">
        <label>Jumlah Penyesuaian</label>
        <input type="number" step="0.01" name="stok_penyesuaian" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label>Keterangan</label>
        <input type="text" name="keterangan" class="form-control">
      </div>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="<?= base_url('stok') ?>" class="btn btn-secondary">Kembali</a>
  </form>
</div>
