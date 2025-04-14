<div class="container mt-4">
    <h3><?= isset($struk) ? 'Edit' : 'Tambah' ?> Struk</h3>

    <form action="<?= base_url('setting/simpan') ?>" method="post" enctype="multipart/form-data">
        <?php if (isset($struk)): ?>
            <input type="hidden" name="id" value="<?= $struk->id ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nama Outlet</label>
            <input type="text" name="nama_outlet" class="form-control" value="<?= $struk->nama_outlet ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= $struk->alamat ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= $struk->email ?? '' ?>">
        </div>

        <div class="form-group">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" class="form-control" value="<?= $struk->no_telepon ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Custom Header</label>
            <input type="text" name="custom_header" class="form-control" value="<?= $struk->custom_header ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Custom Footer</label>
            <input type="text" name="custom_footer" class="form-control" value="<?= $struk->custom_footer ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Logo (jika ingin diganti)</label>
            <input type="file" name="logo" class="form-control-file">
        </div>

        <h5 class="mt-4">Tampilan per Divisi</h5>
        <?php foreach ($divisi as $d): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <strong><?= $d->nama_divisi ?></strong>
                </div>
                <div class="card-body">
                    <?php
                        $cek = isset($tampilan[$d->id]) ? $tampilan[$d->id] : [];
                        $opsi = [
                            'show_logo' => 'Tampilkan Logo',
                            'show_outlet' => 'Tampilkan Nama Outlet',
                            'show_alamat' => 'Tampilkan Alamat',
                            'show_no_telepon' => 'Tampilkan Telepon',
                            'show_custom_header' => 'Custom Header',
                            'show_invoice' => 'Tampilkan Invoice',
                            'show_kasir_order' => 'Kasir Order',
                            'show_kasir_bayar' => 'Kasir Bayar',
                            'show_no_transaksi' => 'No Transaksi',
                            'show_customer' => 'Nama Customer',
                            'show_nomor_meja' => 'Nomor Meja',
                            'show_waktu_order' => 'Waktu Order',
                            'show_waktu_bayar' => 'Waktu Bayar',
                            'show_custom_footer' => 'Custom Footer',
                        ];
                    ?>
                    <?php foreach ($opsi as $key => $label): ?>
                        <div class="form-check">
                            <input type="checkbox" name="tampilan[<?= $d->id ?>][<?= $key ?>]" value="1" class="form-check-input" 
                                <?= isset($cek[$key]) && $cek[$key] ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= $label ?></label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>

        <button type="submit" class="btn btn-primary mt-4">Simpan</button>
        <a href="<?= base_url('setting') ?>" class="btn btn-secondary mt-4">Kembali</a>
    </form>
</div>
