<!DOCTYPE html>
<html>
<head>
    <title>Data Awal</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        #search-existing-results {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            width: 300px; /* Lebar preview */
            z-index: 10; /* Pastikan di atas elemen lainnya */
        }
        #search-existing-results ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        #search-existing-results li {
            padding: 5px;
            cursor: pointer;
        }
        #search-existing-results li:hover {
            background: #f0f0f0;
        }
        .search-container {
            position: relative;
            display: inline-block;
        }    </style>

</head>
<body>
    <!-- <h1>Data Awal</h1> -->
    <a href="<?php echo site_url('data_awal'); ?>"><h1>Data Awal</h1></a>

    <!-- Form Tambah Data -->
    <form action="<?= base_url('data_awal/add') ?>" method="POST">
        <label>Cari Barang:</label>
        <input type="text" id="search-barang" placeholder="Cari barang..." autocomplete="off">
        <div id="search-results"></div>

        <input type="hidden" name="id_db_belanja" id="id_db_belanja" required> <!-- ID DB Belanja -->
        <label>Nama Barang:</label>
        <input type="text" id="nama_barang" readonly><br>

        <label>Merk:</label>
        <input type="text" id="merk" readonly><br>

        <label>Ukuran:</label>
        <input type="text" id="ukuran" readonly><br>

        <label>Harga:</label>
        <input type="text" id="harga" readonly><br>

        <label>Stok Awal:</label>
        <input type="number" name="stok_awal" required><br>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required><br>

        <button type="submit">Tambah</button>
    </form>

    <?php if ($this->session->flashdata('success')): ?>
        <div style="color: green;"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>


    <!-- Form Filter -->
    <form id="filter-form">
        <label>Filter Bulan:</label>
        <select id="filter-month" name="month">
            <?php 
            $currentMonth = date('m');
            for ($i = 1; $i <= 12; $i++): 
                $monthValue = str_pad($i, 2, '0', STR_PAD_LEFT);
            ?>
                <option value="<?= $monthValue ?>" <?= $currentMonth == $monthValue ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <label>Cari Barang yang Sudah Ditambahkan:</label>
        <div class="search-container">
            <input type="text" id="search-barang-existing" placeholder="Cari barang yang sudah ditambahkan..." autocomplete="off">
            <div id="search-existing-results"></div>
        </div>
        <button type="button" id="filter-button">Filter</button>
    </form>


    <!-- Tabel Data -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Nama Bahan Baku</th>
                <th>Tipe</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th>Unit</th>
                <th>Pack</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-tbody">
            <!-- <?php foreach ($data_awal as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['nama_bahan_baku'] ?></td>
                    <td><?= $item['tipe'] ?></td>
                    <td><?= $item['merk'] ?></td>
                    <td><?= $item['ukuran'] ?></td>
                    <td><?= $item['unit'] ?></td>
                    <td><?= $item['pack'] ?></td>
                    <td><?= number_format($item['harga'], 2, ',', '.') ?></td>
                    <td><?= $item['stok_awal'] ?></td>
                    <td><?= $item['tanggal'] ?></td>
                    <td><a href="<?= base_url('data_awal/edit/' . $item['id']) ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?> -->
        </tbody>
    </table>

    <script>
        document.getElementById('search-barang').addEventListener('keyup', function () {
            const keyword = this.value;
            const resultsDiv = document.getElementById('search-results');

            if (keyword.length >= 2) {
                fetch(`<?= base_url('purchase/search') ?>?keyword=${keyword}`)
                    .then(response => response.json())
                    .then(data => {
                        let resultsHTML = '<ul>';
                        data.forEach(item => {
                            resultsHTML += `
                                <li>
                                    ${item.nama_barang}, Merk: ${item.merk}, Ukuran: ${item.ukuran}, Harga: Rp ${item.harga}
                                    <button onclick="fillForm(${JSON.stringify(item).replace(/"/g, '&quot;')})">Tambahkan</button>
                                </li>`;
                        });
                        resultsHTML += '</ul>';
                        resultsDiv.innerHTML = resultsHTML;
                    });
            } else {
                resultsDiv.innerHTML = '';
            }
        });

        function fillForm(item) {
            document.getElementById('id_db_belanja').value = item.id;
            document.getElementById('nama_barang').value = item.nama_barang;
            document.getElementById('merk').value = item.merk;
            document.getElementById('ukuran').value = item.ukuran;
            document.getElementById('harga').value = item.harga;
            document.getElementById('search-results').innerHTML = ''; // Kosongkan hasil pencarian setelah memilih barang
        }

// filter bulan 
        function fetchData(month = '', search = '') {
            $.ajax({
                url: '<?= base_url("data_awal/get_data") ?>',
                type: 'GET',
                data: { month: month, search: search },
                success: function(response) {
                    const data = JSON.parse(response);
                    const tbody = $('#data-tbody');
                    tbody.empty();

                    if (data.length > 0) {
                        data.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.nama_bahan_baku}</td>
                                    <td>${item.nama_tipe}</td>
                                    <td>${item.merk}</td>
                                    <td>${item.ukuran}</td>
                                    <td>${item.unit}</td>
                                    <td>${item.pack}</td>
                                    <td>${item.harga}</td>
                                    <td>${item.stok_awal}</td>
                                    <td>${item.tanggal}</td>
                                    <td>
                                        <a href="<?= base_url('data_awal/edit/') ?>${item.id}">Edit</a>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="12">Tidak ada data ditemukan.</td></tr>');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        }

        $(document).ready(function() {
            const defaultMonth = $('#filter-month').val();

            // Fetch data saat halaman dimuat
            fetchData(defaultMonth, '');

            // Filter berdasarkan tombol
            $('#filter-button').on('click', function() {
                const month = $('#filter-month').val();
                const search = $('#search-barang-existing').val();
                fetchData(month, search);
            });

            // Filter menggunakan tombol Enter
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                $('#filter-button').click();
            });

            // Pencarian barang yang sudah ditambahkan
            $('#search-barang-existing').on('keyup', function() {
                const keyword = $(this).val();
                const resultsDiv = $('#search-existing-results');

                if (keyword.length >= 2) {
                    $.ajax({
                        url: '<?= base_url("data_awal/search_existing_barang") ?>',
                        type: 'GET',
                        data: { keyword: keyword },
                        success: function(response) {
                            const data = JSON.parse(response);
                            let resultsHTML = '<ul>';

                            if (data.length > 0) {
                                data.forEach(item => {
                                    resultsHTML += `
                                        <li onclick="selectBarang('${item.nama_barang}')">${item.nama_barang}</li>
                                    `;
                                });
                            } else {
                                resultsHTML += '<li>Tidak ada data ditemukan.</li>';
                            }

                            resultsHTML += '</ul>';
                            resultsDiv.html(resultsHTML);
                        }
                    });
                } else {
                    resultsDiv.html('');
                }
            });

            // Pilih barang dari hasil pencarian
            window.selectBarang = function(nama_barang) {
                $('#search-barang-existing').val(nama_barang);
                $('#search-existing-results').html('');
            };
        });
            </script>
</body>
</html>
