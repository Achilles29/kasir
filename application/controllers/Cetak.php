<?php
class Cetak extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Printer_model'); // untuk ambil URL printer
    }

    public function divisi($divisi, $id_transaksi) {
        $transaksi = $this->Transaksi_model->get_transaksi($id_transaksi);
        $items     = $this->Transaksi_model->get_detail_transaksi($id_transaksi);
        $printer   = $this->Printer_model->get_by_divisi_nama($divisi); // misal "bar", "kitchen"

        if (!$printer) {
            show_error("Printer untuk divisi $divisi tidak ditemukan.");
        }

        $detail = [];
        foreach ($items as $item) {
            $detail[] = [
                "nama" => $item['nama_produk'],
                "qty" => $item['jumlah'],
                "harga" => $item['harga']
            ];
        }

        $payload = [
            "title" => "Namua POS - " . strtoupper($divisi),
            "no_transaksi" => $transaksi['no_transaksi'],
            "tanggal" => $transaksi['tanggal'],
            "items" => $detail,
            "total" => $transaksi['total_bayar']
        ];

        // kirim ke service Python (localhost atau IP)
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "http://localhost:".$printer['port']."/cetak", // contoh: http://localhost:3001/cetak
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        echo "Cetak ke printer $divisi: <pre>$response</pre>";
    }
}

}