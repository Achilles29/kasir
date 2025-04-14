<div style="max-width: 300px; margin: auto; font-family: monospace; font-size: 14px; text-align:center">

    <h5>Preview Struk untuk Printer: <?= strtoupper($printer['lokasi_printer']) ?> (<?= $printer['printer_name'] ?>)
    </h5>

    <?php if (!empty($tampilan['show_logo']) && !empty($struk['logo'])): ?>
    <img src="<?= base_url('uploads/' . $struk['logo']) ?>" style="max-height: 80px;"><br>
    <?php endif; ?>

    <?php if (!empty($tampilan['show_outlet'])): ?>
    <strong><?= $struk['nama_outlet'] ?></strong><br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_alamat'])): ?>
    <?= $struk['alamat'] ?><br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_no_telepon'])): ?>
    Telp: <?= $struk['no_telepon'] ?><br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_custom_header'])): ?>
    <hr><?= $struk['custom_header'] ?>
    <hr>
    <?php endif; ?>

    <?php if (!empty($tampilan['show_no_transaksi'])): ?>
    INVOICE #12345<br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_kasir_order'])): ?>
    Order: Budi<br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_kasir_bayar'])): ?>
    Kasir: Ani<br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_customer'])): ?>
    Customer: Andi<br>
    <?php endif; ?>
    <?php if (!empty($tampilan['show_nomor_meja'])): ?>
    Meja: A3<br>
    <?php endif; ?>

    <hr>
    <table width="100%" style="text-align:left">
        <tr>
            <td>Americano</td>
            <td align="right">20.000</td>
        </tr>
        <tr>
            <td>Espresso</td>
            <td align="right">18.000</td>
        </tr>
    </table>
    <hr>
    <b>Total: 38.000</b><br>

    <?php if (!empty($tampilan['show_custom_footer'])): ?>
    <hr><?= $struk['custom_footer'] ?><br>
    <?php endif; ?>
</div>