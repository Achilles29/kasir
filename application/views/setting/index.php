<h3><?= $title ?></h3>
<a href="<?= base_url('setting/form_data_struk') ?>" class="btn btn-primary mb-3">Data Perusahaan</a>

<?php if (!empty($struk['logo'])): ?>
<div class="mb-3">
    <label>Logo Saat Ini:</label><br>
    <img src="<?= base_url('uploads/' . $struk['logo']) ?>" style="height:80px;">
</div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Lokasi Printer</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($printer as $p): ?>
        <tr>
            <td><?= strtoupper($p->lokasi_printer) ?> (<?= $p->printer_name ?>)</td>
            <td>
                <a href="<?= base_url('setting/form_tampilan_struk/' . $p->id) ?>"
                    class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('setting/preview/' . $p->id) ?>" class="btn btn-info btn-sm"
                    target="_blank">Preview</a>
            </td>
        </tr>
        <?php endforeach ?>

    </tbody>
</table>