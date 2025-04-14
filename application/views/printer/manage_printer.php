<h2>Manajemen Printer</h2>

<!-- Tambah Printer -->
<form id="form-tambah-printer">
    <input type="text" id="printer_name" placeholder="Nama Printer" required>
    <select id="printer_type">
        <option value="bluetooth">Bluetooth</option>
        <option value="network">Network</option>
        <option value="usb">USB</option>
    </select>
    <select id="divisi">
        <option value="kasir">Kasir</option>
        <option value="bar">Bar</option>
        <option value="kitchen">Kitchen</option>
        <option value="waiters">Waiters</option>
    </select>
    <button type="submit">Tambah</button>
</form>

<!-- List Printer -->
<table>
    <thead>
        <tr>
            <th>Nama Printer</th>
            <th>Jenis</th>
            <th>Divisi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="printer-list"></tbody>
</table>

<script>
$(document).ready(function() {
    function loadPrinters() {
        $.get("<?= site_url('printer/index'); ?>", function(data) {
            let html = "";
            data.printers.forEach(function(printer) {
                html += `<tr>
                    <td>${printer.printer_name}</td>
                    <td>${printer.printer_type}</td>
                    <td>${printer.divisi}</td>
                    <td><button onclick="hapusPrinter(${printer.id})">Hapus</button></td>
                </tr>`;
            });
            $("#printer-list").html(html);
        }, "json");
    }

    $("#form-tambah-printer").submit(function(e) {
        e.preventDefault();
        $.post("<?= site_url('printer/tambah_printer'); ?>", {
            printer_name: $("#printer_name").val(),
            printer_type: $("#printer_type").val(),
            divisi: $("#divisi").val()
        }, function(response) {
            if (response.status === "success") {
                loadPrinters();
            } else {
                alert("Gagal menambahkan printer");
            }
        }, "json");
    });

    window.hapusPrinter = function(printer_id) {
        $.post("<?= site_url('printer/hapus_printer'); ?>", { printer_id }, function(response) {
            if (response.status === "success") {
                loadPrinters();
            } else {
                alert("Gagal menghapus printer");
            }
        }, "json");
    };

    loadPrinters();
});
</script>
