<style>
#tableRefund thead th {
    vertical-align: middle;
    text-align: center;
}

#tableRefund tbody td.text-center {
    text-align: center !important;
}

#tableRefund tbody td.text-end {
    text-align: right !important;
}
</style>



<!-- File: application/views/kasir/daftar_refund.php -->
<div class="container-fluid mt-4">
    <h4 class="mb-4"><i class="fas fa-undo-alt"></i> Laporan Refund</h4>

    <div class="row mb-3">
        <div class="col-md-3">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" id="btnFilter"><i class="fas fa-search"></i> Filter</button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableRefund" class="table table-bordered table-hover table-striped">
                    <thead class="thead-light text-center">
                        <!-- Rata tengah -->
                        <tr>
                            <th>Kode Refund</th>
                            <th>No Transaksi</th>
                            <th>Refund (Rp)</th>
                            <th>Metode</th>
                            <th>Customer</th>
                            <th>Meja</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & DataTables CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    function load_table(tanggal_awal, tanggal_akhir, keyword = '') {
        $('#tableRefund').DataTable({
            destroy: true,
            processing: true,
            ajax: {
                url: "<?= base_url('kasir/get_refund_data_ajax') ?>",
                data: {
                    tanggal_awal,
                    tanggal_akhir,
                    keyword
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'kode_refund'
                },
                {
                    data: 'no_transaksi'
                },
                {
                    data: 'total_refund',
                    className: 'text-end',
                    render: d => `Rp ${parseInt(d).toLocaleString('id-ID')}`
                },
                {
                    data: 'metode_pembayaran',
                    className: 'text-center'
                },
                {
                    data: 'customer'
                },
                {
                    data: 'nomor_meja',
                    className: 'text-center'
                },
                {
                    data: 'waktu',
                    className: 'text-center',
                    render: d => moment(d).format('DD/MM/YYYY HH:mm')
                },
                {
                    data: 'kode_refund',
                    className: 'text-center',
                    render: function(kode) {
                        return `
                        <a href="<?= base_url('kasir/detail_refund_kode/') ?>${kode}" class="btn btn-sm btn-info" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="<?= base_url('kasir/cetak_refund/') ?>${kode}" target="_blank" class="btn btn-sm btn-danger" title="Cetak">
                            <i class="fas fa-print"></i>
                        </a>
                    `;
                    }
                }

            ],


            pageLength: 10,
            lengthMenu: [
                [10, 20, 100, -1],
                [10, 20, 100, 'Semua']
            ]
        });
    }

    const today = moment().format('YYYY-MM-DD');
    load_table(today, today);

    $('#btnFilter').on('click', function() {
        const awal = $('#tanggal_awal').val();
        const akhir = $('#tanggal_akhir').val();
        const keyword = $('#keyword').val();
        load_table(awal, akhir, keyword);
    });
});
</script>