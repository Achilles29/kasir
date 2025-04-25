<!DOCTYPE html>
<html>

<head>
    <title>Cetak Refund</title>
    <style>
    body {
        font-family: monospace;
        font-size: 14px;
    }

    .line {
        border-bottom: 1px dashed #000;
        margin: 10px 0;
    }
    </style>
</head>

<body onload="window.print()">
    <h3>NOTIFIKASI REFUND</h3>
    <p><strong>Kode Refund:</strong> <?= $refund[0]->kode_refund ?></p>
    <p><strong>No Transaksi:</strong> <?= $refund[0]->no_transaksi ?></p>
    <div class="line"></div>

    <?php
    $divisi = '';
    foreach ($refund as $row):
        if ($divisi != $row->nama_divisi):
            if ($divisi != '') echo "<div class='line'></div>";
            echo "<strong>DIVISI: {$row->nama_divisi}</strong><br>";
            $divisi = $row->nama_divisi;
        endif;
    ?>
    - <?= $row->nama_produk ?> (<?= $row->jumlah ?> x <?= number_format($row->harga, 0, ',', '.') ?>)<br>
    <?php if ($row->nama_extra): ?>
    &nbsp;&nbsp;+ <?= $row->nama_extra ?> (<?= number_format($row->harga, 0, ',', '.') ?>)
    <?php endif; ?>
    <?php endforeach; ?>

    <div class="line"></div>
    <p>JANGAN DIPROSES!</p>
    <p>Refund oleh kasir: <?= $this->session->userdata('nama') ?></p>
    <p>Waktu: <?= date('d-m-Y H:i:s') ?></p>
</body>

</html>