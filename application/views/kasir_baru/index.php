<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POS - Kasir Baru</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <h2>POS - Namua Coffee & Eatery</h2>

    <h4>Pesanan Belum Dibayar</h4>
    <div id="pending-orders"></div>

    <button id="btn-cetak" class="btn btn-dark">Cetak</button>

    <!-- Modal Pilihan Cetak -->
    <div id="modalCetak">
        <h5>Pilih Opsi Cetak</h5>
        <button class="cetak-struk" data-type="struk">Cetak Struk</button>
        <button class="cetak-struk" data-type="bar">Cetak Divisi Bar</button>
        <button class="cetak-struk" data-type="kitchen">Cetak Divisi Kitchen</button>
        <button class="cetak-struk" data-type="waiters">Cetak Divisi Waiters</button>
    </div>

    <script>
        let selectedOrderId = null;

        function loadPendingOrders() {
            $.get("<?= site_url('KasirBaru/load_pending_orders'); ?>", function(response) {
                let html = "";
                response.forEach(order => {
                    html += `<div class="pesanan-item" data-id="${order.id}">
                        <strong>${order.no_transaksi}</strong> - Rp ${order.total_pembayaran.toLocaleString('id-ID')}
                    </div>`;
                });
                $("#pending-orders").html(html);
            }, "json");
        }

        $(document).on("click", ".pesanan-item", function () {
            $(".pesanan-item").removeClass("selected");
            $(this).addClass("selected");
            selectedOrderId = $(this).data("id");
        });

        $("#btn-cetak").click(function () {
            if (!selectedOrderId) {
                alert("Pilih pesanan terlebih dahulu!");
                return;
            }
            $("#modalCetak").show();
        });

        $(".cetak-struk").click(function () {
            let cetakType = $(this).data("type");

            $.get("<?= site_url('KasirBaru/cetak_struk/') ?>" + cetakType + "/" + selectedOrderId, function(response) {
                if (response.status === "success") {
                    alert("Cetakan dikirim ke printer: " + response.printer);
                } else {
                    alert("Gagal mencetak: " + response.message);
                }
            }, "json");
        });

        loadPendingOrders();
    </script>
</body>
</html>
