<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container mt-4">
    <h4 class="mb-3"><i class="fas fa-gift"></i> Promo Voucher Otomatis</h4>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalPromo"><i class="fas fa-plus"></i>
        Tambah Promo</button>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark text-center">
            <tr>
                <th>Nama Promo</th>
                <th>Trigger</th>
                <th>Nilai</th>
                <th>Produk Trigger</th>
                <th>Jenis Voucher</th>
                <th>Nilai Voucher</th>
                <th>Min Pembelian</th>
                <th>Produk</th>
                <th>Max Diskon</th>
                <th>Maks. Voucher</th>
                <th>Masa Berlaku</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $row): ?>
            <tr>
                <td><?= $row->nama_promo ?></td>
                <td class="text-center"><?= ucfirst($row->tipe_trigger) ?></td>
                <td class="text-center"><?= $row->nilai ?? '-' ?></td>
                <td>
                    <?php if (!empty($row->produk_ids)): ?>
                    <?php 
                        $ids = json_decode($row->produk_ids ?? '[]');
                        $ids = is_array($ids) ? $ids : [];
                        if (!empty($ids)) {
                            $produk = $this->db->where_in('id', $ids)->get('pr_produk')->result();
                            echo implode(', ', array_column($produk, 'nama_produk'));
                        } else {
                            echo '-';
                        }
                        ?>

                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>

                <td class="text-center"><?= ucfirst($row->jenis) ?></td>
                <td class="text-right">
                    <?php if ($row->jenis == 'persentase'): ?>
                    <?= $row->nilai_voucher ?>%
                    <?php else: ?>
                    Rp <?= number_format($row->nilai_voucher, 0, ',', '.') ?>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?= $row->min_pembelian ? 'Rp ' . number_format($row->min_pembelian, 0, ',', '.') : '-' ?></td>
                <td>
                    <?php if (!empty($row->produk_id)): ?>
                    <?php
                        $produk = $this->db->get_where('pr_produk', ['id' => $row->produk_id])->row();
                        echo $produk ? $produk->nama_produk : '-';
                    ?>
                    <?php else: ?>
                    -
                    <?php endif; ?>

                </td>

                <td class="text-right">
                    <?= $row->max_diskon ? 'Rp ' . number_format($row->max_diskon, 0, ',', '.') : '-' ?></td>
                <td class="text-center"><?= $row->maksimal_voucher ?></td>
                <td class="text-center"><?= $row->masa_berlaku ?></td>
                <td class="text-center">
                    <?= $row->aktif == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' ?>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info btn-edit" data-id="<?= $row->id ?>"><i
                            class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $row->id ?>"><i
                            class="fas fa-trash"></i></button>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="modalPromo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('promo_voucher_auto/simpan') ?>" method="post" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel">Tambah Promo Voucher</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="promo_id">
                <div class="form-group">
                    <label>Nama Promo</label>
                    <input type="text" name="nama_promo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tipe Trigger</label>
                    <select name="tipe_trigger" class="form-control" required>
                        <option value="minimal_transaksi">Minimal Transaksi</option>
                        <option value="produk_tertentu">Produk Tertentu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nilai</label>
                    <input type="number" name="nilai" class="form-control" required>
                </div>
                <div class="form-group position-relative">
                    <label for="search_produk">Produk Trigger</label>
                    <input type="text" id="search_produk" class="form-control" placeholder="🔍 Ketik nama produk...">
                    <ul id="produk-list" class="list-group position-absolute w-100"
                        style="z-index: 9999; display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <!-- tidak perlu div selected-produk -->
                </div>


                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input type="text" name="masa_berlaku" class="form-control" placeholder="contoh: 7 hari" required>
                </div>
                <div class="form-group">
                    <label>Jenis Voucher</label>
                    <select name="jenis" class="form-control" required>
                        <option value="nominal">Nominal</option>
                        <option value="persen">Persen</option>
                    </select>
                </div>
                <div class="form-group position-relative">
                    <label for="produk_id_input">Produk Syarat Penggunaan Voucher (Opsional)</label>
                    <input type="text" id="produk_id_input" class="form-control" placeholder="🔍 Cari produk...">
                    <input type="hidden" name="produk_id" id="produk_id">
                    <ul id="produk-syarat-list" class="list-group position-absolute w-100"
                        style="z-index: 9999; display: none; max-height: 200px; overflow-y: auto;"></ul>
                </div>

                <div class="form-group">
                    <label>Nilai Voucher</label>
                    <input type="number" name="nilai_voucher" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Minimal Pembelian</label>
                    <input type="number" name="min_pembelian" class="form-control">
                </div>
                <div class="form-group">
                    <label>Maksimal Diskon</label>
                    <input type="number" name="max_diskon" class="form-control">
                </div>
                <div class="form-group">
                    <label>Maksimal Voucher</label>
                    <input type="number" name="maksimal_voucher" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="aktif" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    let selectedProdukIds = [];

    $('#search_produk').on('keyup', function() {
        const keyword = $(this).val().trim();

        if (keyword.length > 1) {
            $.get("<?= site_url('produk/ajax_search'); ?>", {
                q: keyword
            }, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(function(item) {
                        html += `
                        <li class="list-group-item list-group-item-action select-produk"
                            data-id="${item.id}" data-nama="${item.nama_produk}">
                            ${item.nama_produk}
                        </li>`;
                    });
                } else {
                    html = '<li class="list-group-item text-muted">Produk tidak ditemukan</li>';
                }
                $('#produk-list').html(html).show();
            }, 'json');
        } else {
            $('#produk-list').hide();
        }
    });

    $(document).on('click', '.select-produk', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        $('#search_produk').val(nama);
        // simpan ke hidden field sebagai string biasa (tanpa array)
        $('input[name="produk_ids"]').remove();
        $('<input>').attr({
            type: 'hidden',
            name: 'produk_ids',
            value: id
        }).appendTo('form.modal-content');

        $('#produk-list').hide();
    });

    // Hapus produk dari daftar pilihan
    $(document).on('click', '.remove-produk', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        selectedProdukIds = selectedProdukIds.filter(i => i != id);
        $(this).parent().remove();
        updateHiddenProdukIds();
    });

    function updateHiddenProdukIds() {
        $('input[name="produk_ids"]').remove(); // Pastikan tidak menumpuk
        $('<input>').attr({
            type: 'hidden',
            name: 'produk_ids',
            value: selectedProdukIds.join(',') // Simpan sebagai "1,2,3"
        }).appendTo('form.modal-content');
    }

    // Sembunyikan list saat klik luar
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#search_produk, #produk-list").length) {
            $("#produk-list").hide();
        }
    });


    $('#produk_id_input').on('keyup', function() {
        let keyword = $(this).val().trim();
        if (keyword.length > 1) {
            $.get("<?= site_url('produk/ajax_search') ?>", {
                q: keyword
            }, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        html += `<li class="list-group-item list-group-item-action produk-syarat-item"
                            data-id="${item.id}" data-nama="${item.nama_produk}">
                            ${item.nama_produk}
                        </li>`;
                    });
                } else {
                    html = `<li class="list-group-item text-muted">Produk tidak ditemukan</li>`;
                }
                $('#produk-syarat-list').html(html).show();
            }, 'json');
        } else {
            $('#produk-syarat-list').hide();
        }
    });

    $(document).on('click', '.produk-syarat-item', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        $('#produk_id_input').val(nama);
        $('#produk_id').val(id);
        $('#produk-syarat-list').hide();
    });


    // Submit form
    $('#modalPromo form').submit(function(e) {
        e.preventDefault();

        // pastikan produk_ids tersimpan
        updateHiddenProdukIds();

        $.post($(this).attr('action'), $(this).serialize(), function(response) {
            if (response.status === 'ok') {
                $('#modalPromo').modal('hide');
                Swal.fire('Berhasil', 'Promo berhasil disimpan!', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal', 'Gagal menyimpan data promo', 'error');
            }
        }, 'json');
    });





    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');

        $.get("<?= site_url('promo_voucher_auto/get_by_id/') ?>" + id, function(data) {
            $('#promo_id').val(data.id);
            $('[name="nama_promo"]').val(data.nama_promo);
            $('[name="tipe_trigger"]').val(data.tipe_trigger);
            $('[name="nilai"]').val(data.nilai);
            $('[name="masa_berlaku"]').val(data.masa_berlaku);
            $('[name="jenis"]').val(data.jenis);
            $('[name="nilai_voucher"]').val(data.nilai_voucher);
            $('[name="min_pembelian"]').val(data.min_pembelian);
            $('[name="max_diskon"]').val(data.max_diskon);
            $('[name="maksimal_voucher"]').val(data.maksimal_voucher);
            $('[name="aktif"]').val(data.aktif);
            $('#produk_id').val(data.produk_id);

            // cari nama produk_id
            $.get("<?= site_url('produk/get_by_id_ajax/') ?>" + data.produk_id, function(res) {
                $('#produk_id_input').val(res.nama_produk);
            }, 'json');

            // tampilkan produk_ids yang multiple
            selectedProdukIds = data.produk_ids ? data.produk_ids.split(',') : [];
            $('#selected-produk').html('');
            selectedProdukIds.forEach(id => {
                $.get("<?= site_url('produk/get_by_id_ajax/') ?>" + id, function(res) {
                    $('#selected-produk').append(`
                    <span class="badge badge-primary mr-1 mb-1">
                        ${res.nama_produk}
                        <a href="#" class="text-white ml-1 remove-produk" data-id="${res.id}">&times;</a>
                    </span>
                `);
                }, 'json');
            });

            $('#modalPromo').modal('show');
        }, 'json');
    });

    // ✅ FIX: DELETE
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Yakin hapus promo ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.post("<?= site_url('promo_voucher_auto/hapus/') ?>" + id, function(res) {
                    try {
                        const data = typeof res === 'string' ? JSON.parse(res) : res;
                        if (data.status === 'ok') {
                            Swal.fire('Terhapus', 'Promo berhasil dihapus', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            Swal.fire('Gagal', 'Data tidak bisa dihapus', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Respon server tidak valid', 'error');
                    }
                });

            }
        });
    });

});
</script>