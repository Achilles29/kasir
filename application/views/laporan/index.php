<!-- Di header (CSS) -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="container-fluid">

    <!-- JUDUL -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <h4 class="mb-0 fw-bold">Detail Penjualan</h4>
            <i class="fas fa-sync-alt text-success"></i>
            <span class="badge bg-warning">⭐</span>
        </div>
    </div>

    <!-- FORM FILTER -->
    <form class="card shadow-sm p-3 mb-4" id="filterForm">
        <div class="row g-2 align-items-end">

            <!-- Pencarian -->
            <div class="col-md-4 col-lg-4">
                <label class="form-label">Cari Transaksi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" id="searchInput" class="form-control"
                        placeholder="Cari transaksi...">
                </div>
            </div>

            <!-- Tanggal Awal -->
            <div class="col-md-3 col-lg-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control"
                    value="<?= $tanggal_awal ?? date('Y-m-d') ?>">
            </div>

            <!-- Tanggal Akhir -->
            <div class="col-md-3 col-lg-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control"
                    value="<?= $tanggal_akhir ?? date('Y-m-d') ?>">
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-2 col-lg-2">
                <label class="form-label d-block invisible">_</label>
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>

        </div>
    </form>



    TABEL TRANSAKSI
    <div id="dataTransaksi">
        <?php $this->load->view('laporan/tabel_transaksi', ['transaksi' => $transaksi]); ?>
    </div>
</div>

<!-- MODAL FILTER -->
<div class="modal fade" id="modalFilter" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formFilterExtra">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-4 border-end">
                        <ul class="nav flex-column nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill"
                                    href="#statusBayar">Status Pembayaran</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#jenisOrder">Jenis
                                    Order</a></li>
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="statusBayar">
                                <label>Status Pembayaran</label>
                                <select name="status_pembayaran[]" class="form-select" multiple>
                                    <option value="LUNAS">Lunas</option>
                                    <option value="BELUM">Belum Lunas</option>
                                </select>
                            </div>
                            <div class="tab-pane fade" id="jenisOrder">
                                <label>Jenis Order</label>
                                <select name="jenis_order[]" class="form-select" multiple>
                                    <option value="Free Table">Free Table</option>
                                    <option value="Take Away">Take Away</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="reset" class="btn btn-outline-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Atur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Footer -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
$(function() {
    const today = moment().format('DD/MM/YYYY');

    $('#tanggalRange').daterangepicker({
        autoUpdateInput: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: "Proses",
            cancelLabel: "Batal"
        },
        ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
            '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ]
        }
    }, function(start, end, label) {
        $('#tanggalTampil').text(`${start.format('DD/MM/YYYY')} - ${end.format('DD/MM/YYYY')}`);
        $('#filterForm').submit();
    });

    // Trigger AJAX filter
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= base_url('laporan/filter') ?>",
            type: "GET",
            data: $(this).serialize(),
            beforeSend: () => {
                $('#dataTransaksi').html(
                    '<div class="text-center text-muted py-5">Memuat data...</div>');
            },
            success: (res) => {
                $('#dataTransaksi').html(res);
            },
            error: () => {
                $('#dataTransaksi').html(
                    '<div class="text-center text-danger py-5">Gagal memuat data.</div>'
                );
            }
        });
    });

    $('#searchInput').on('input', () => $('#filterForm').submit());
});
</script>