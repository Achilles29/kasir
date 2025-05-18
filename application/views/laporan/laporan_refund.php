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

.bg-maroon {
    background-color: #800000;
    color: white;
}

.table-refund th {
    background-color: #800000;
    color: white;
    text-align: center;
}

.refund-extra {
    padding-left: 25px;
    font-style: italic;
    color: #555;
}

.modal-title {
    font-weight: 600;
}

#modalDetail .modal-body {
    padding: 1.5rem;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Detail Refund</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>


            <div class="modal-body" id="detailBody">
                <div class="text-center">Loading...</div>
            </div>
        </div>

    </div>
</div>



<!-- DataTables + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
const base_url = "<?= base_url() ?>";

$(document).ready(function() {
    function load_table(tanggal_awal, tanggal_akhir, keyword = '') {
        $('#tableRefund').DataTable({
            destroy: true,
            processing: true,
            ajax: {
                url: base_url + "kasir/get_refund_data_ajax",
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
                            <button type="button" class="btn btn-sm btn-info btn-detail" data-kode="${kode}" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-cetak" data-kode="${kode}" title="Cetak Refund">
                                <i class="fas fa-print"></i>
                            </button>`;
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
    $(document).on('click', '.btn-detail', function() {
        const kode = $(this).data('kode');
        const myModal = new bootstrap.Modal(document.getElementById('modalDetail'));
        myModal.show();

        $('#detailBody').html('<div class="text-center">Memuat data...</div>');

        $.ajax({
            url: base_url + 'laporan/laporan_refund_modal_detail',
            method: 'GET',
            data: {
                kode_refund: kode
            },
            success: function(res) {
                $('#detailBody').html(res);
            },
            error: function() {
                $('#detailBody').html('<div class="text-danger">Gagal memuat data.</div>');
            }
        });
    });;

    const today = moment().format('YYYY-MM-DD');
    load_table(today, today);

    $('#btnFilter').on('click', function() {
        const awal = $('#tanggal_awal').val();
        const akhir = $('#tanggal_akhir').val();
        const keyword = ''; // kosongkan dulu pencarian keyword manual
        load_table(awal, akhir, keyword);
    });

    $(document).on('click', '.btn-cetak', function() {
        const kode_refund = $(this).data('kode');

        Swal.fire({
            title: 'Cetak Refund?',
            text: "Cetak refund ini ke printer?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#800000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Cetak!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(base_url + 'kasir/cetak_refund_internal', {
                    kode_refund: kode_refund
                }, function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Sukses', res.message, 'success');
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });

});
</script>