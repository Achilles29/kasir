<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Antrian Dapur</title>
    <style>
    body {
        margin: 0;
        font-family: Arial;
        background: #1c1c1c;
        color: white;
    }

    .container {
        display: flex;
        height: 100vh;
    }

    .left-panel,
    .right-panel {
        padding: 20px;
        overflow-y: auto;
    }

    .left-panel {
        width: 40%;
        background: #292929;
        border-right: 2px solid #444;
    }

    .right-panel {
        width: 60%;
        background: #222;
    }

    .order-item {
        padding: 15px;
        margin-bottom: 10px;
        background: #444;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }

    .order-item:hover {
        background: #555;
    }

    .order-item.checked {
        background: #666;
        color: #aaa;
    }

    .order-item.flash {
        animation: flashAnim 1s ease infinite alternate;
    }

    .order-detail {
        margin-bottom: 15px;
        padding: 15px;
        background: #333;
        border-radius: 10px;
    }

    .order-detail.cancelled {
        background: #802020;
    }

    .extra-item {
        font-size: 14px;
        margin-left: 20px;
        color: #ccc;
    }

    .extra-item.cancelled {
        color: #ff9999;
    }

    .btn-check {
        background: green;
        padding: 5px 10px;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }

    .checkbox {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid white;
        margin-right: 8px;
        vertical-align: middle;
    }

    .checkbox.checked {
        background: green;
    }

    .order-item.selected {
        border: 2px solid yellow;
        background: #555 !important;
    }

    @keyframes flashAnim {
        0% {
            background: #ff4444;
        }

        100% {
            background: #444;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="left-panel" id="order-list">
            <h2>Antrian</h2>
        </div>
        <div class="right-panel" id="order-detail">
            <h2>Detail Pesanan</h2>
        </div>
    </div>

    <audio id="notif-sound" src="<?= base_url('assets/sound/notification.mp3'); ?>" preload="auto"></audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    let lastMaxOrderId = 0;
    let selectedOrderId = null;
    let lastKnownId = 0;

    let lastKnownId = 0;

    function longPolling() {
        $.ajax({
            url: "<?= base_url('antrian/cek_transaksi_baru'); ?>",
            method: "GET",
            timeout: 30000, // 30 detik timeout biar stabil
            success: function(response) {
                const result = JSON.parse(response);
                if (result.last_id > lastKnownId) {
                    console.log("Orderan Baru!");
                    $("#notif-sound")[0].play();
                    loadTransaksi(); // Hanya refresh daftar
                    lastKnownId = result.last_id;
                }
            },
            complete: function() {
                // Setelah selesai, panggil longPolling lagi
                setTimeout(longPolling, 1000); // Delay kecil antar polling
            }
        });
    }


    // Load awal daftar
    loadTransaksi(function() {
        longPolling();
    });

    function loadTransaksi(callback = null) {
        $.getJSON("<?= base_url('antrian/get_transaksi'); ?>", function(data) {
            let orderListHTML = '<h2>Antrian</h2>';

            $.each(data, function(i, item) {
                orderListHTML += `
                <div class="order-item ${item.semua_checked == 1 ? 'checked' : ''}" id="order-${item.id}" onclick="selectOrder(${item.id})">
                    <strong>#${item.no_transaksi}</strong> - ${item.customer}<br>Meja: ${item.nomor_meja}<br><small>${item.created_at}</small>
                </div>
            `;
            });

            $("#order-list").html(orderListHTML);

            // Kalau user sudah pernah klik transaksi, tetap highlight order terakhir
            if (selectedOrderId !== null) {
                $("#order-" + selectedOrderId).addClass('selected');
            }

            if (callback) {
                callback();
            }
        });
    }


    function selectOrder(id) {
        selectedOrderId = id;

        $(".order-item").removeClass('selected');
        $("#order-" + id).addClass('selected');

        loadDetail(id);
    }

    function loadDetail(id) {
        selectedOrderId = id;

        // Hapus selected sebelumnya
        $(".order-item").removeClass('selected');
        // Tambahkan selected ke yang dipilih
        $("#order-" + id).addClass('selected');

        // Load detail dari server
        $.getJSON("<?= base_url('antrian/get_detail/'); ?>" + id, function(data) {
            $("#order-detail").html('<h2>Detail Pesanan</h2>');

            if (data.length === 0) {
                $("#order-detail").append('<p>Tidak ada produk.</p>');
            } else {
                // Pisahkan data: normal dan batal/refund
                const normal = data.filter(item => item.status == 'BERHASIL');
                const batal = data.filter(item => item.status != 'BERHASIL');

                normal.forEach(item => {
                    $("#order-detail").append(generateDetailHTML(item, false));
                });

                if (batal.length > 0) {
                    $("#order-detail").append('<hr><h4>Produk Batal / Refund</h4>');
                    batal.forEach(item => {
                        $("#order-detail").append(generateDetailHTML(item, true));
                    });
                }
            }
        });
    }

    function generateDetailHTML(item, isCancel = false) {
        const color = isCancel ? 'cancelled' : '';
        const statusText = isCancel ? `<span style="color:red;">(${item.status})</span>` : '';
        const checked = item.is_checked == 1 ? 'checked' : '';

        let html = `
        <div class="order-detail ${color}">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>${item.nama_produk}</strong> x ${item.jumlah} Rp${item.harga} ${statusText}
                    <div>${item.catatan ? '<em>Catatan: ' + item.catatan + '</em>' : ''}</div>
                </div>
                <div>
                    <input type="checkbox" ${checked} disabled>
                </div>
            </div>
    `;

        if (item.extra.length > 0) {
            item.extra.forEach(ex => {
                const exColor = (ex.status != 'BERHASIL') ? 'cancelled' : '';
                const exStatus = (ex.status != 'BERHASIL') ? `(${ex.status})` : '';
                html += `
                <div class="extra-item ${exColor}">
                    - ${ex.nama_produk_extra} x${ex.jumlah} Rp${ex.harga} ${exStatus}
                </div>
            `;
            });
        }

        html += '</div>';
        return html;
    }

    // Load awal
    loadTransaksi();
    setInterval(loadTransaksi, 3000);
    </script>

</body>

</html>