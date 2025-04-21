<div class="container-fluid p-4">
    <h3 class="mb-4">Pesanan Terbayar <i class="fas fa-check-circle text-success"></i></h3>

    <div class="row mb-3">
        <div class="col-md-2">
            <select id="filter-status" class="form-control">
                <option value="">Semua Status</option>
                <option value="LUNAS">Lunas</option>
                <option value="REFUND">Refund</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" id="tanggal-awal" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" id="tanggal-akhir" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="text" id="search" class="form-control" placeholder="Cari No Transaksi / Customer...">
        </div>
        <div>
            <label>Show
                <select id="perPage" class="form-control form-control-sm d-inline-block" style="width: auto;">
                    <option value="10" selected>10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="9999">Semua</option>
                </select>
                entries
            </label>
        </div>

        <div class="col-md-2 mb-2">
            <button id="filter" class="btn btn-primary btn-block">
                <i class="fas fa-search"></i> Filter
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <button class="btn btn-secondary btn-sm" id="resetFilter"><i class="fas fa-redo"></i> Reset</button>
            <button class="btn btn-success btn-sm" id="exportExcel"><i class="fas fa-file-excel"></i> Export
                Excel</button>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading" style="display:none;" class="text-center mb-2">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>No Transaksi</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-body"></tbody>
            </table>

            <!-- PAGINATION -->
            <nav>
                <ul class="pagination justify-content-center" id="pagination-pesanan"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
const base_url = "<?= base_url(); ?>";

let currentPage = 1;
let perPage = 10;
let timeout = null;
let currentData = [];

function setTodayDefault() {
    const today = new Date().toISOString().slice(0, 10);
    $("#tanggal-awal").val(today);
    $("#tanggal-akhir").val(today);
}

function showLoading() {
    $("#loading").show();
}

function hideLoading() {
    $("#loading").hide();
}

function statusBadge(status) {
    if (status === "LUNAS") {
        return `<span class="badge badge-success">${status}</span>`;
    } else if (status === "REFUND") {
        return `<span class="badge badge-danger">${status}</span>`;
    } else if (status === "PIUTANG") {
        return `<span class="badge badge-warning text-dark">${status}</span>`;
    } else {
        return `<span class="badge badge-secondary">${status}</span>`;
    }
}

function loadPesanan(page = 1) {
    const tanggal_awal = $("#tanggal-awal").val();
    const tanggal_akhir = $("#tanggal-akhir").val();
    const search = $("#search").val();
    const statusFilter = $("#filter-status").val();
    perPage = parseInt($("#perPage").val());

    showLoading();

    $.get(base_url + "kasir/get_pesanan_terbayar", {
        tanggal_awal,
        tanggal_akhir,
        search
    }, function(res) {
        let allData = JSON.parse(res);

        // Filter LUNAS/REFUND di frontend
        if (statusFilter) {
            allData = allData.filter(item => item.status_pembayaran === statusFilter);
        }

        currentData = allData;

        const totalData = allData.length;
        const totalPages = Math.ceil(totalData / perPage);
        const start = (page - 1) * perPage;
        const end = perPage === 9999 ? totalData : start + perPage;
        const pageData = allData.slice(start, end);

        let html = "";
        let totalRefundHariIni = 0;

        pageData.forEach((item, index) => {
            if (item.status_pembayaran === "REFUND") {
                totalRefundHariIni += parseInt(item.total_pembayaran);
            }

            html += `
            <tr>
                <td>${start + index + 1}</td>
                <td>${item.no_transaksi}</td>
                <td>${item.customer || '-'}</td>
                <td>${item.tanggal}</td>
                <td>Rp ${parseInt(item.total_pembayaran).toLocaleString('id-ID')}</td>
                <td>${statusBadge(item.status_pembayaran)}</td>
                <td>
                    <button class="btn btn-sm btn-info lihat-rincian" data-id="${item.id}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                </td>
            </tr>
            `;
        });

        $("#tabel-body").html(html);

        let pagination = '';
        if (perPage !== 9999) {
            for (let i = 1; i <= totalPages; i++) {
                pagination += `<li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
        }
        $("#pagination-pesanan").html(pagination);

        hideLoading();
    });
}

function downloadExcel(data) {
    let csv = 'No,No Transaksi,Customer,Tanggal,Total Bayar,Status\n';
    data.forEach((item, index) => {
        csv +=
            `${index + 1},"${item.no_transaksi}","${item.customer || '-'}","${item.tanggal}","${item.total_pembayaran}","${item.status_pembayaran}"\n`;
    });

    const blob = new Blob(["\uFEFF" + csv], {
        type: 'text/csv;charset=utf-8;'
    });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `PesananTerbayar_${new Date().toISOString().slice(0,10)}.csv`;
    link.click();
}

$(document).ready(function() {
    setTodayDefault();
    loadPesanan();

    $("#filter, #filter-status, #perPage").on("change click", function() {
        currentPage = 1;
        loadPesanan(currentPage);
    });

    $("#resetFilter").click(function() {
        setTodayDefault();
        $("#search").val('');
        $("#filter-status").val('');
        $("#perPage").val('10');
        currentPage = 1;
        loadPesanan(currentPage);
    });

    $("#exportExcel").click(function() {
        downloadExcel(currentData);
    });

    $("#search").on("keyup", function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            currentPage = 1;
            loadPesanan(currentPage);
        }, 400);
    });

    $(document).on("click", "#pagination-pesanan a", function(e) {
        e.preventDefault();
        const page = $(this).data("page");
        currentPage = page;
        loadPesanan(page);
    });

    $("#tabel-body").on("click", ".lihat-rincian", function() {
        const id = $(this).data("id");
        window.open(base_url + "kasir/rincian_pesanan/" + id, "_blank");
    });
});
</script>