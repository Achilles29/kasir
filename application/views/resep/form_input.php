<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>

<div class="container-fluid">
  <h4 class="mb-3"><?= $title ?></h4>

  <ul class="nav nav-tabs mb-3" id="resepTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="produk-tab" data-bs-toggle="tab" data-bs-target="#produk" type="button" role="tab">Resep Produk</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="base-tab" data-bs-toggle="tab" data-bs-target="#base" type="button" role="tab">Resep Base</button>
    </li>
  </ul>

  <div class="tab-content">
    <!-- FORM RESEP PRODUK -->
    <div class="tab-pane fade show active" id="produk" role="tabpanel">
      <form action="<?= base_url('resep/simpan') ?>" method="post" class="card p-3 shadow-sm">
        <input type="hidden" name="tipe" value="produk">
        <div class="row g-2">
          <div class="col-md-4">
            <label>Produk</label>
            <select name="produk_id" class="form-select" required>
              <option value="">Pilih Produk</option>
              <?php foreach ($produk as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nama_produk'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label>Bahan Baku</label>
            <select name="bahan_id" class="form-select" required>
              <option value="">Pilih Bahan</option>
              <?php foreach ($bahan as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" required>
          </div>
          <div class="col-md-2">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label>HPP</label>
            <input type="number" name="hpp" class="form-control" step="0.01">
          </div>
          <div class="col-md-2 d-grid">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </form>
    </div>

    <!-- FORM RESEP BASE -->
    <div class="tab-pane fade" id="base" role="tabpanel">
      <form action="<?= base_url('resep/simpan') ?>" method="post" class="card p-3 shadow-sm mt-3">
        <input type="hidden" name="tipe" value="base">
        <div class="row g-2">
          <div class="col-md-4">
            <label>Base (Produk)</label>
            <select name="produk_id" class="form-select" required>
              <option value="">Pilih Base</option>
              <?php foreach ($produk as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nama_produk'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label>Bahan Baku</label>
            <select name="bahan_id" class="form-select" required>
              <option value="">Pilih Bahan</option>
              <?php foreach ($bahan as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" required>
          </div>
          <div class="col-md-2">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label>HPP</label>
            <input type="number" name="hpp" class="form-control" step="0.01">
          </div>
          <div class="col-md-2 d-grid">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
