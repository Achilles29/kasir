async function scanPrinter() {
	try {
		const device = await navigator.bluetooth.requestDevice({
			acceptAllDevices: true,
			optionalServices: ["battery_service"],
		});

		let printerName = device.name || "Printer Tidak Diketahui";
		let printerId = device.id;

		let printerList = document.getElementById("printerList");
		let listItem = document.createElement("li");
		listItem.className = "list-group-item";
		listItem.innerHTML = `${printerName} 
                    <button class="btn btn-success btn-sm float-end" onclick="setPrinter('${printerId}', '${printerName}')">Gunakan</button>`;
		printerList.appendChild(listItem);
	} catch (error) {
		alert("Gagal scan printer: " + error.message);
	}
}

function setPrinter(id, name) {
	$.post(
		"<?= site_url('printer/save_printer') ?>",
		{ printer_id: id, printer_name: name },
		function (response) {
			alert(
				response.status === "success"
					? "Printer berhasil disimpan!"
					: "Gagal menyimpan printer!"
			);
			location.reload();
		},
		"json"
	);
}

document.getElementById("scanPrinter").addEventListener("click", scanPrinter);
