<style>
.laporan-void-wrapper {
    background-color: #fdfde0;
    padding: 30px;
}

.laporan-void-wrapper h3 {
    font-weight: 600;
    color: #2c3e50;
}

.laporan-void-wrapper input,
.laporan-void-wrapper button,
.laporan-void-wrapper select {
    border-radius: 8px !important;
}

.laporan-void-wrapper .form-control {
    box-shadow: none;
    border: 1px solid #ccc;
}

.laporan-void-wrapper .btn-primary {
    background-color: #9b00ff;
    border: none;
    transition: 0.2s;
}

.laporan-void-wrapper .btn-primary:hover {
    background-color: #6c00d6;
}

.laporan-void-wrapper .table thead {
    background-color: #2c3e50;
    color: white;
    font-size: 14px;
    text-transform: uppercase;
}

.laporan-void-wrapper .table td,
.laporan-void-wrapper .table th {
    vertical-align: middle;
}

#pagination button {
    margin: 0 3px;
}
</style>

<div class="container-fluid laporan-void-wrapper">
    <div class="laporan-void-wrapper">
        <h3 class="mb-4 text-center"><?= $title ?></h3>

        <div class="row mb-3 justify-content-center align-items-center g-2 text-center">
            <div class="col-md-2">
                <input type="date" id="tanggal_awal" class="form-control form-control-sm" value="<?= date('Y-m-01') ?>">
            </div>
            <div class="col-md-2">
                <input type="date" id="tanggal_akhir" class="form-control form-control-sm" value="<?= date('Y-m-t') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari kode void / no transaksi...">
            </div>
            <div class="col-md-2">
                <select id="per_page" class="form-control form-control-sm">
                    <option value="10" selected>10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="99999">Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <button onclick="loadData(1)" class="btn btn-sm btn-primary w-100">Tampilkan</button>
            </div>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover text-center mb-0" id="void-table">
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Kode Void</th>
                        <th>No Transaksi</th>
                        <th>Tanggal Void</th>
                        <th>Nilai Void</th>
                        <th>Alasan</th>
                        <th>Otorisasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>

            </table>
        </div>
        <div class="p-3 text-center" id="pagination"></div>
    </div>
</div>


<script>
function loadData(page = 1) {
    let awal = $('#tanggal_awal').val();
    let akhir = $('#tanggal_akhir').val();
    let search = $('#search').val();
    let perPage = $('#per_page').val();

    $.getJSON('<?= base_url('laporan/ajax_void') ?>', {
        tanggal_awal: awal,
        tanggal_akhir: akhir,
        search: search,
        page: page,
        per_page: perPage
    }, function(res) {
        let rows = '';
        let no = (res.page - 1) * res.per_page + 1;
        res.data.forEach(v => {
            rows += `<tr>
                        <td>${no++}</td>
                        <td>${v.kode_void}</td>
                        <td>${v.no_transaksi}</td>
                        <td>${new Date(v.created_at).toLocaleString()}</td>
                        <td>Rp ${parseInt(v.total_void).toLocaleString('id-ID')}</td>
                        <td>${v.alasan}</td>
                        <td>${v.nama_pegawai ?? '-'}</td>
                        <td>
                            <a href="<?= base_url('laporan/detail_void/') ?>${v.kode_void}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>`;
        });

        $('#void-table tbody').html(rows);

        // Pagination
        let totalPage = Math.ceil(res.total / res.per_page);
        let pag = '';
        for (let i = 1; i <= totalPage; i++) {
            pag +=
                `<button onclick="loadData(${i})" class="btn btn-sm ${i === res.page ? 'btn-dark' : 'btn-outline-dark'} mx-1">${i}</button>`;
        }
        $('#pagination').html(pag);
    });
}


// Auto-load saat halaman pertama dibuka
$(document).ready(() => loadData());
$('#search').on('keyup', function() {
    loadData(1);
});

$('#tanggal_awal, #tanggal_akhir, #per_page').on('change', function() {
    loadData(1);
});
</script>