<div class="preview-struk">
    <?php if ($pengaturan['show_logo'] && !empty($pengaturan['logo'])): ?>
        <img id="preview-logo" src="<?= base_url('uploads/'.$pengaturan['logo']) ?>" alt="Logo Outlet">
    <?php endif; ?>

    <?php if ($pengaturan['show_outlet']): ?>
        <p><strong><?= $pengaturan['nama_outlet'] ?></strong></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_address']): ?>
        <p><?= $pengaturan['alamat'] ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_phone']): ?>
        <p>Telp: <?= $pengaturan['no_telepon'] ?></p>
    <?php endif; ?>

    <hr>

    <?php if ($pengaturan['show_invoice']): ?>
        <p>No Nota: <?= $transaksi['no_transaksi']; ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_order_time']): ?>
        <p>Waktu Order: <?= date("d/m/Y H:i", strtotime($transaksi['waktu_order'])); ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_cashier_order']): ?>
        <p>Kasir Order: <?= $transaksi['kasir_order']; ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_customer'] && !empty($transaksi['customer'])): ?>
        <p>Customer: <?= $transaksi['customer']; ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_order_type']): ?>
        <p>Jenis Order: <?= $transaksi['jenis_order_id']; ?></p>
    <?php endif; ?>

    <?php if ($pengaturan['show_table_number'] && !empty($transaksi['nomor_meja'])): ?>
        <p>Nomor Meja: <?= $transaksi['nomor_meja']; ?></p>
    <?php endif; ?>

    <hr>

    <table width="100%">
        <?php foreach ($detail_transaksi as $item): ?>
            <tr>
                <td><?= $item['jumlah']; ?>x</td>
                <td><?= $item['nama_produk']; ?></td>
                <td><?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php if (!empty($item['catatan'])): ?>
                <tr>
                    <td colspan="3">* <?= $item['catatan']; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

    <hr>

    <p>Total: Rp <?= number_format($transaksi['total_pembayaran'], 0, ',', '.'); ?></p>

    <?php if ($pengaturan['show_footer']): ?>
        <hr>
        <p><?= nl2br($pengaturan['custom_footer']); ?></p>
    <?php endif; ?>
</div>
