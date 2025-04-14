<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="container">
    <h2>Tambah Divisi</h2>
    <form action="<?= site_url('divisi/tambah'); ?>" method="post">
        <div class="form-group">
            <label for="nama_divisi">Nama Divisi*</label>
            <input type="text" class="form-control" name="nama_divisi" required>
        </div>
        <div class="form-group">
            <label for="urutan_tampilan">Urutan Tampilan*</label>
            <input type="number" class="form-control" name="urutan_tampilan" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>