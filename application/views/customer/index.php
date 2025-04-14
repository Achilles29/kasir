<!-- Bootstrap JS + Popper.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">
    <h2 class="text-center">Daftar Pelanggan</h2>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="Cari pelanggan...">
        </div>
        <div class="col-md-4">
            <select id="limit" class="form-control">
                <option value="10">10 Baris</option>
                <option value="25">25 Baris</option>
                <option value="50">50 Baris</option>
                <option value="all">Tampilkan Semua</option>
            </select>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#customerModal" id="add-customer">Tambah Pelanggan</button>
        </div>
    </div>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>NAMA</th>
                <th>KODE PELANGGAN</th>
                <th>ALAMAT</th>
                <th>TELEPON</th>
                <th>JENIS KELAMIN</th>
                <th>POIN</th>
                <th>SALDO DEPOSIT</th>
                <th></th> <!-- Untuk titik tiga -->
            </tr>
        </thead>
        <tbody id="customer-list"></tbody>

    </table>

    <div class="text-center">
        <ul class="pagination"></ul>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah/Edit Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="customer-id">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" id="customer-name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" id="customer-dob" class="form-control">
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select id="customer-gender" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="customer-email" class="form-control">
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" id="customer-phone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea id="customer-address" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" id="customer-foto" name="foto" class="form-control">
                    <img id="foto-preview" src="" width="100" style="display:none;" class="mb-2">
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="save-customer">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    function loadCustomers(page = 1) {
        let search = $("#search").val();
        let limit = $("#limit").val();
        let offset = (page - 1) * limit;

        if (limit === "all") {
            limit = 10000;
            offset = 0;
        }

        $.ajax({
            url: "<?= site_url('customer/load_customers'); ?>",
            type: "GET",
            data: { start: offset, limit: limit, search: search },
            success: function(response) {
                let data = JSON.parse(response);
                let html = "";
                $.each(data.customers, function(index, customer) {
                html += `<tr>
                    <td>${customer.nama}</td>
                    <td>${customer.kode_pelanggan}</td>
                    <td>${customer.alamat ?? '-'}</td>
                    <td>${customer.telepon}</td>
                    <td>${customer.jenis_kelamin ?? '-'}</td>
                    <td>${customer.total_poin ?? 0}</td>
                    <td>Rp 0</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenu${customer.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ⋮
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu${customer.id}">
                                <a class="dropdown-item edit-customer" href="#" data-id="${customer.id}">Ubah</a>
                                <a class="dropdown-item" href="<?= site_url('customer/transaksi/'); ?>${customer.id}">Lihat Daftar Transaksi Pelanggan</a>
                                <a class="dropdown-item" href="<?= site_url('customer/detail/'); ?>${customer.id}">Lihat Detail Pelanggan</a>
                                <a class="dropdown-item delete-customer text-danger" href="#" data-id="${customer.id}">Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                });
               $("#customer-list").html(html);
                renderPagination(data.total, limit, page);
            }
        });
    }

    function renderPagination(total, limit, currentPage) {
        let totalPages = Math.ceil(total / limit);
        let paginationHtml = "";

        if (totalPages > 1 && limit !== "all") {
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link pagination-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
        }

        $(".pagination").html(paginationHtml);
    }

    $("#save-customer").click(function(){
        let id = $("#customer-id").val();
        let nama = $("#customer-name").val();
        let telepon = $("#customer-phone").val();
        let alamat = $("#customer-address").val();
        let tanggal_lahir = $("#customer-dob").val();
        let jenis_kelamin = $("#customer-gender").val();
        let email = $("#customer-email").val();

        if(nama === '' || telepon === '') {
            alert("Nama dan Telepon wajib diisi!");
            return;
        }

        let url = id ? "<?= site_url('customer/edit_customer/'); ?>" + id : "<?= site_url('customer/add_customer'); ?>";
        
        let formData = new FormData();
        formData.append("nama", nama);
        formData.append("telepon", telepon);
        formData.append("alamat", alamat);
        formData.append("tanggal_lahir", tanggal_lahir);
        formData.append("jenis_kelamin", jenis_kelamin);
        formData.append("email", email);
        formData.append("foto", $('#customer-foto')[0].files[0]);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                let res = JSON.parse(response);
                if(res.status === "success") {
                    $("#customerModal").modal("hide");
                    $("body").removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    loadCustomers();
                } else {
                    alert("Terjadi kesalahan!");
                }
            }
        });

    });


    $(document).on("click", ".edit-customer", function(){
        let id = $(this).data("id");

        $.ajax({
            url: "<?= site_url('customer/get_customer/'); ?>" + id,
            type: "GET",
            success: function(response) {
                let data = JSON.parse(response);
                $("#customer-id").val(data.id);
                $("#customer-name").val(data.nama);
                $("#customer-phone").val(data.telepon);
                $("#customer-address").val(data.alamat);
                $("#customer-dob").val(data.tanggal_lahir); // Tambah baris ini
                $("#customer-gender").val(data.jenis_kelamin); // Tambah baris ini
                $("#customer-email").val(data.email); // Tambah baris ini
                $("#foto-preview").attr("src", "<?= base_url('uploads/foto_pelanggan/'); ?>" + data.foto).show();
                $("#customerModal").modal("show");
            }
        });
    });

    $(document).on("click", ".delete-customer", function(){
        let id = $(this).data("id");

        if(confirm("Apakah Anda yakin ingin menghapus pelanggan ini?")) {
            $.ajax({
                url: "<?= site_url('customer/delete_customer/'); ?>" + id,
                type: "POST",
                success: function(response) {
                    let res = JSON.parse(response);
                    if(res.status === "success") {
                        loadCustomers();
                    } else {
                        alert("Gagal menghapus pelanggan!");
                    }
                }
            });
        }
    });

    $("#search").on("input", function() { loadCustomers(); });

    $(document).on("click", ".pagination-link", function(e){
        e.preventDefault();
        let page = $(this).data("page");
        loadCustomers(page);
    });

    $("#limit").on("change", function() { loadCustomers(); });

    loadCustomers();
});
</script>
