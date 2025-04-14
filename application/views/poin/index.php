<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Poin</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider { background-color: #28a745; }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">Pengaturan Poin</h3>

    <div class="card p-4">
        <h5 class="mb-3">Aturan Poin</h5>

        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahPoin">Tambah Poin</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jenis Poin</th>
                    <th>Produk / Min Pembelian</th>
                    <th>Nilai Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($poin as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', $row['jenis_point'])) ?></td>
                    <td>
                        <?php if ($row['jenis_point'] == 'per_produk'): ?>
                            <?= $row['nama_produk'] ?> <!-- Ambil dari join produk -->
                        <?php else: ?>
                            Min. Rp <?= number_format($row['min_pembelian'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['nilai_point'] ?> Poin</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-poin" 
                            data-id="<?= $row['id'] ?>" 
                            data-jenis="<?= $row['jenis_point'] ?>" 
                            data-produk="<?= $row['produk_id'] ?>" 
                            data-min="<?= $row['min_pembelian'] ?>" 
                            data-nilai="<?= $row['nilai_point'] ?>">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm hapus-poin" data-id="<?= $row['id'] ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Poin -->
<div class="modal fade" id="modalTambahPoin" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah/Edit Poin</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formPoin">
                    <input type="hidden" name="id" id="poin_id">
                    <div class="form-group">
                        <label>Jenis Poin</label>
                        <select class="form-control" name="jenis_point" id="jenis_point">
                            <option value="per_produk">Per Produk</option>
                            <option value="per_pembelian">Per Pembelian</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Produk</label>
                        <input type="text" class="form-control" id="cari_produk" placeholder="Ketik nama produk...">
                        <input type="hidden" name="produk_id" id="produk_id">
                        <div id="hasil_pencarian" class="list-group mt-2"></div>
                    </div>

                    <div class="form-group" id="min_pembelian_section" style="display: none;">
                        <label>Minimal Pembelian (Rp)</label>
                        <input type="number" class="form-control" name="min_pembelian">
                    </div>
                    <div class="form-group">
                        <label>Nilai Poin</label>
                        <input type="number" class="form-control" name="nilai_point" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#jenis_point").change(function(){
        if ($(this).val() == "per_produk") {
            $("#produk_section").show();
            $("#min_pembelian_section").hide();
        } else {
            $("#produk_section").hide();
            $("#min_pembelian_section").show();
        }
    });

    $("#formPoin").submit(function(e){
        e.preventDefault();
        $.post("<?= site_url('poin/simpan_poin') ?>", $(this).serialize(), function(response){
            location.reload();
        }, "json");
    });

});

$(document).ready(function() {
    $("#cari_produk").on("keyup", function() {
        let query = $(this).val();
        if (query.length < 1) {
            $("#hasil_pencarian").html(""); // Kosongkan hasil pencarian jika input kosong
            return;
        }

        $.ajax({
            url: "<?= site_url('poin/cari_produk') ?>",
            method: "GET",
            data: { search: query },
            dataType: "json",
            success: function(data) {
                let output = "";
                if (data.length > 0) {
                    data.forEach(function(item) {
                        output += `<a href="#" class="list-group-item list-group-item-action pilih-produk" data-id="${item.id}" data-nama="${item.text}">${item.text}</a>`;
                    });
                } else {
                    output = '<div class="list-group-item">Produk tidak ditemukan</div>';
                }
                $("#hasil_pencarian").html(output);
            }
        });
    });

    // Pilih produk dari hasil pencarian
    $(document).on("click", ".pilih-produk", function(e) {
        e.preventDefault();
        let produkId = $(this).data("id");
        let produkNama = $(this).data("nama");

        $("#cari_produk").val(produkNama);
        $("#produk_id").val(produkId); // Simpan ID produk ke input hidden
        $("#hasil_pencarian").html(""); // Kosongkan hasil setelah memilih
    });

});

$(document).ready(function(){
    // Ketika tombol Edit diklik
    $(".edit-poin").click(function(){
        let id = $(this).data("id");
        let jenis = $(this).data("jenis");
        let produk = $(this).data("produk");
        let minPembelian = $(this).data("min");
        let nilai = $(this).data("nilai");

        $("#poin_id").val(id); // Simpan ID di input hidden
        $("#jenis_point").val(jenis).trigger('change');
        $("#cari_produk").val($(this).data("produk_nama")); // Tampilkan nama produk
        $("#produk_id").val(produk); // Simpan ID produk ke hidden input
        $("input[name='min_pembelian']").val(minPembelian);
        $("input[name='nilai_point']").val(nilai);
        
        $("#modalTambahPoin").modal('show');
    });


    // Fungsi untuk hapus poin
    $(".hapus-poin").click(function(){
        let id = $(this).data("id");
        if (confirm("Hapus aturan poin ini?")) {
            $.post("<?= site_url('poin/hapus_poin') ?>", {id: id}, function(){
                location.reload();
            });
        }
    });
});

</script>

</body>
</html>
