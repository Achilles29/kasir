<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cetak_bbm extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bbm_model'); // pastikan ini ada
    }

    public function index()
    {
        $data['title'] = "Cetak BBM";
        $data['spbu'] = $this->Bbm_model->get_all_spbu(); // ambil via model
        $this->load->view('templates/header', $data);
        $this->load->view('cetak_bbm/index', $data);
        $this->load->view('templates/footer');
    }

    public function cetak($id)
    {
        $spbu = $this->Bbm_model->get_spbu($id);
        if (!$spbu) show_404();

        $data['spbu'] = $spbu;
        $this->load->view('cetak_bbm/template_cetak', $data);
    }
    
private function send_to_python_service($lokasi_printer, $text) {
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);

    if (!$printer) return "Printer tidak ditemukan di database.";

    // Default: jika tidak dikenali
    $portMap = [
        'KASIR'   => 3000,
        'BAR'     => 3001,
        'KITCHEN' => 3002,
        'CHECKER'  => 3003
    ];

    $upper = strtoupper($printer['lokasi_printer']);
    $port = isset($portMap[$upper]) ? $portMap[$upper] : 3000;

    $url = "http://localhost:{$port}/cetak";

    $payload = [
        'text' => $text
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_CONNECTTIMEOUT => 2, // ⏱️ max waktu tunggu koneksi 2 detik
        CURLOPT_TIMEOUT => 5        // ⏱️ max keseluruhan waktu request 5 detik
    ]);

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) return "Tidak terhubung ke printer ($url): $error";

    $res = json_decode($result, true);
    return (isset($res['status']) && $res['status'] === 'success') ? true : ($res['message'] ?? 'Gagal cetak');
}


public function print_struk()
{
    $id = $this->input->post('id');
    $this->load->model(['Printer_model', 'Setting_model']);

    $spbu = $this->Bbm_model->get_spbu($id);
    if (!$spbu) {
        echo json_encode(['status' => 'error', 'message' => 'SPBU tidak ditemukan.']);
        return;
    }

    $printers = $this->Printer_model->get_all();
    $hasil = [];

    foreach ($printers as $printer) {
        if (strtoupper($printer['lokasi_printer']) !== 'KASIR') continue;

        // Generate struk dari model
        $struk = $this->Bbm_model->generate_struk_bbm($spbu);

        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            $hasil[] = "⚠️ Struk kosong untuk printer KASIR.";
            continue;
        }

        // Kirim ke Python server (gunakan fungsi send_to_python_service dari Kasir)
        $res = $this->send_to_python_service($printer['lokasi_printer'], $struk);

        if ($res === true) {
            $hasil[] = "✅ Struk berhasil dicetak ke {$printer['lokasi_printer']}.";
        } else {
            $hasil[] = "❌ Gagal cetak ke {$printer['lokasi_printer']}.";
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => implode("\n", $hasil)
    ]);
}


}