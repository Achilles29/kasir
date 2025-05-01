<!-- Di header (CSS) -->

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
                <!-- ✅ Perubahan -->
                <input type="date" name="tanggal_awal" id="tanggal-awal" class="form-control"
                    value="<?= date('Y-m-d') ?>">
            </div>

            <!-- Tanggal Akhir -->
            <div class="col-md-3 col-lg-3">
                <label class="form-label">Tanggal Akhir</label>
                <!-- ✅ Perubahan -->
                <input type="date" name="tanggal_akhir" id="tanggal-akhir" class="form-control"
                    value="<?= date('Y-m-d') ?>">
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-2 col-lg-2">
                <label class="form-label d-block invisible">_</label>
                <!-- ✅ Perubahan warna tombol -->
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Dropdown tampilan per halaman -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <label class="me-2">Tampilkan:
                <select id="perPage" class="form-select form-select-sm d-inline-block" style="width: auto;">
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="99999">Semua</option>
                </select> entri
            </label>
        </div>
        <div id="pagination" class="d-flex flex-wrap gap-1"></div>
    </div>


    <div id="dataTransaksi">
        <?php $this->load->view('laporan/tabel_transaksi', ['transaksi' => $transaksi]); ?>
    </div>

</div>


<!-- MODAL FILTER -->
<!-- <div class="modal fade" id="modalFilter" tabindex="-1">
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
</div> -->

<!-- JS Footer -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
$(function() {
    function loadData(page = 1) {
        const tanggal_awal = $('#tanggal-awal').val();
        const tanggal_akhir = $('#tanggal-akhir').val();
        const search = $('#searchInput').val();
        const per_page = $('#perPage').val();

        $.ajax({
            url: "<?= base_url('laporan/filter') ?>",
            type: "GET",
            data: {
                tanggal_awal,
                tanggal_akhir,
                search,
                page,
                per_page
            },
            beforeSend: () => {
                $('#dataTransaksi').html(
                    '<div class="text-center py-5 text-muted">Memuat data...</div>');
            },
            success: (res) => {
                const data = JSON.parse(res);
                $('#dataTransaksi').html(renderTable(data.transaksi));
                renderPagination(data.total_data, data.page, data.per_page);
            }
        });
    }

    function renderTable(transaksi) {
        if (transaksi.length === 0) {
            return '<div class="text-center text-muted py-5">Tidak ada data transaksi.</div>';
        }

        let html = `
            <table class="table table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th>No Transaksi</th><th>Waktu Order</th><th>Waktu Bayar</th>
                        <th>Jenis Order</th><th>Total Penjualan</th><th>Aksi</th>
                    </tr>
                </thead><tbody>
        `;

        transaksi.forEach(t => {
            html += `
                <tr>
                    <td>${t.no_transaksi}</td>
                    <td>${t.waktu_order ?? '-'}</td>
                    <td>${t.waktu_bayar ?? '-'}</td>
                    <td>${t.jenis_order}</td>
                    <td>Rp ${parseInt(t.total_penjualan).toLocaleString('id-ID')}</td>
                    <td><a href="<?= base_url('laporan/detail/') ?>${t.id}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> Detail</a></td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        return html;
    }

    function renderPagination(total, page, per_page) {
        const totalPages = Math.ceil(total / per_page);
        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            html +=
                `<button class="btn btn-sm ${i === page ? 'btn-primary' : 'btn-outline-secondary'} page-btn" data-page="${i}">${i}</button>`;
        }
        $('#pagination').html(html);
    }

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadData(1);
    });

    $('#searchInput').on('keyup', function() {
        $('#filterForm').submit();
    });

    $('#perPage').on('change', function() {
        loadData(1);
    });

    $(document).on('click', '.page-btn', function() {
        const page = $(this).data('page');
        loadData(page);
    });

    // Load awal
    loadData();
});
</script>