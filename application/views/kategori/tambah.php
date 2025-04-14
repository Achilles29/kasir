        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>


<div class="container">
    <h2><?= isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori'; ?></h2>
    <form action="<?= isset($kategori) ? site_url('kategori/edit/'.$kategori['id']) : site_url('kategori/tambah'); ?>" method="post">

        <div class="container">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori*</label>
                <input type="text" class="form-control" name="nama_kategori" required>
            </div>
            <div class="form-group">
                <label for="urutan">Urutan*</label>
                <input type="number" class="form-control" name="urutan" required>
            </div>
            <div class="form-group">
                <label for="pr_divisi_id">Departemen</label>
                <select class="form-control" name="pr_divisi_id">
                    <option value="">Pilih Departemen</option>
                    <?php foreach ($divisi as $d): ?>
                        <option value="<?= $d['id']; ?>"><?= $d['nama_divisi']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="1">Tampil di Menu</option>
                    <option value="2">Tidak Tampil di Menu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
</div>