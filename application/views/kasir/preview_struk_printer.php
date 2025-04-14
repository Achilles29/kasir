<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Preview Cetakan - <?= strtoupper($printer['lokasi_printer']) ?></title>
    <style>
    body {
        font-family: monospace;
        background: #f8f8f8;
        padding: 20px;
    }

    .preview-box {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        max-width: 300px;
        margin: auto;
    }

    .btn {
        padding: 10px 20px;
        margin: 5px;
        font-size: 14px;
    }

    .text-center {
        text-align: center;
    }
    </style>
</head>

<body>

    <h2 class="text-center">Preview Cetakan - <?= strtoupper($printer['lokasi_printer']) ?></h2>
    <p class="text-center">Transaksi: <?= $transaksi['no_transaksi'] ?> | <?= $transaksi['customer'] ?></p>

    <div class="preview-box">
        <?php if (!empty($tampilan['show_logo']) && !empty($struk_data['logo'])): ?>
        <div class="text-center">
            <img src="<?= base_url('uploads/' . $struk_data['logo']) ?>" style="max-height: 80px;"><br>
        </div>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_outlet'])): ?>
        <div class="text-center"><strong><?= $struk_data['nama_outlet'] ?></strong></div>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_alamat'])): ?>
        <div class="text-center"><?= $struk_data['alamat'] ?></div>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_no_telepon'])): ?>
        <div class="text-center">Telp: <?= $struk_data['no_telepon'] ?></div>
        <?php endif; ?>


        <?php if (!empty($tampilan['show_custom_header'])): ?>
        <hr>
        <div class="text-center"><?= nl2br($struk_data['custom_header']) ?></div>
        <hr>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_no_transaksi'])): ?>
        No: <?= $transaksi['no_transaksi'] ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_kasir_order'])): ?>
        Order: <?= $transaksi['kasir_order_username'] ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_kasir_bayar'])): ?>
        Kasir: <?= $transaksi['kasir_bayar_username'] ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_customer'])): ?>
        Customer: <?= $transaksi['customer'] ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_nomor_meja'])): ?>
        Meja: <?= $transaksi['nomor_meja'] ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_waktu_order'])): ?>
        Order: <?= date('d-m-Y H:i', strtotime($transaksi['waktu_order'])) ?><br>
        <?php endif; ?>

        <?php if (!empty($tampilan['show_waktu_bayar']) && $transaksi['waktu_bayar']): ?>
        Bayar: <?= date('d-m-Y H:i', strtotime($transaksi['waktu_bayar'])) ?><br>
        <?php endif; ?>

        <hr>
        <?php foreach ($transaksi['items'] as $item): ?>
        <?php
                // Ambil produk untuk cek divisi
                $produk = $this->db
                    ->select('k.pr_divisi_id')
                    ->from('pr_produk p')
                    ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
                    ->where('p.id', $item['pr_produk_id'])
                    ->get()->row_array();

                if (!empty($printer['divisi']) && $produk['pr_divisi_id'] != $printer['divisi']) continue;
            ?>
        <?php
            $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';
            $subtotal = $item['harga'] * $item['jumlah'];
            ?>
        <div style="display: flex; justify-content: space-between;">
            <span><?= $item['jumlah'] ?>x <?= $item['nama_produk'] ?></span>
            <?php if ($isKasir): ?>
            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
            <?php endif; ?>
        </div>

        <?php
            // Tampilkan extra
            $this->db->where('detail_transaksi_id', $item['id']);
            $extras = $this->db->get('pr_detail_extra')->result_array();
            foreach ($extras as $ex):
                $extra_produk = $this->db->get_where('pr_produk_extra', ['id' => $ex['pr_produk_extra_id']])->row_array();
                $nama_extra = $extra_produk['nama_extra'] ?? 'Extra';
                $harga_extra = $ex['harga'] * $ex['jumlah'];
            ?>
        <div style="display: flex; justify-content: space-between; margin-left: 10px;">
            <span><?= $ex['jumlah'] ?>x <?= $nama_extra ?></span>
            <?php if ($isKasir): ?>
            <span>Rp <?= number_format($harga_extra, 0, ',', '.') ?></span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if (!empty($item['catatan'])): ?>
        <div style="margin-left: 10px;"><em>Note: <?= $item['catatan'] ?></em></div>
        <?php endif; ?>


        <?php endforeach; ?>
        <hr>
        <?php if (!empty($tampilan['show_custom_footer'])): ?>
        <hr>
        <div class="text-center"><?= nl2br($struk_data['custom_footer']) ?></div>
        <?php endif; ?>

    </div>

    <form method="post" action="<?= site_url('kasir/cetak_pending_printer') ?>" class="text-center mt-3">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi['id'] ?>">
        <input type="hidden" name="lokasi_printer" value="<?= $printer['lokasi_printer'] ?>">
        <input type="hidden" name="struk_text" value="<?= htmlentities($preview_struk) ?>">
        <button type="submit" class="btn btn-success">🖨️ Cetak Sekarang</button>
        <a href="<?= site_url('kasir') ?>" class="btn btn-secondary">← Kembali</a>
    </form>

</body>

</html>