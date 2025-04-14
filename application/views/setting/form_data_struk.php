<h3><?= $title ?></h3>
<!-- <form method="post" action="<?= base_url('setting/simpan_data_struk') ?>"> -->
<form action="<?= base_url('setting/simpan_data_struk') ?>" method="post" enctype="multipart/form-data">
 
    <div class="form-group">
        <label>Nama Outlet</label>
        <input type="text" name="nama_outlet" class="form-control" value="<?= $struk['nama_outlet'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"><?= $struk['alamat'] ?? '' ?></textarea>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $struk['email'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label>No Telepon</label>
        <input type="text" name="no_telepon" class="form-control" value="<?= $struk['no_telepon'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label>Custom Header</label>
        <input type="text" name="custom_header" class="form-control" value="<?= $struk['custom_header'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label>Custom Footer</label>
        <input type="text" name="custom_footer" class="form-control" value="<?= $struk['custom_footer'] ?? '' ?>">
    </div>
<div class="form-group">
  <label>Upload Logo</label>
  <input type="file" name="logo" class="form-control">
  <?php if (!empty($struk['logo'])): ?>
    <img src="<?= base_url('uploads/' . $struk['logo']) ?>" style="height:60px;">
  <?php endif; ?>
</div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

