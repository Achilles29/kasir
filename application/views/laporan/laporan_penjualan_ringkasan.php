<?php
$total_penjualan = 0;
$total_pembayaran = 0;
$total_piutang = 0;
$penjualan_bersih = 0;

foreach ($transaksi_ringkasan as $t) {

    $total_penjualan += $t['total_pembayaran'] !== null ? $t['total_pembayaran'] : $t['total_penjualan'];
    if ($t['total_pembayaran'] !== null) {
        $total_pembayaran += $t['total_pembayaran'];
    } else {
        $total_piutang += $t['total_penjualan'];
    }
}
?>

<div class="row mb-4 g-3">
    <div class="col-md-2">
        <div class="card shadow-sm border-0 border-bottom border-success border-3">
            <div class="card-body text-center">
                <div class="fw-bold text-muted small">Total Penjualan</div>
                <div class="text-success fs-6 fw-bold">Rp <?= number_format($total_penjualan, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 border-bottom border-primary border-3">
            <div class="card-body text-center">
                <div class="fw-bold text-muted small">Total Transaksi</div>
                <div class="text-primary fs-6 fw-bold"><?= count($transaksi_ringkasan) ?></div>

            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 border-bottom border-info border-3">
            <div class="card-body text-center">
                <div class="fw-bold text-muted small">Penjualan Bersih</div>
                <div class="text-info fs-6 fw-bold">Rp <?= number_format($penjualan_bersih, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 border-bottom border-purple border-3">
            <div class="card-body text-center">
                <div class="fw-bold text-muted small">Total Pembayaran</div>
                <div class="text-purple fs-6 fw-bold">Rp <?= number_format($total_pembayaran, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm border-0 border-bottom border-warning border-3">
            <div class="card-body text-center">
                <div class="fw-bold text-muted small">Total Piutang</div>
                <div class="text-warning fs-6 fw-bold">Rp <?= number_format($total_piutang, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
</div>