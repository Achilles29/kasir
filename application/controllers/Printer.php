<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Printer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Printer_model');
        $this->load->model('Divisi_model');
    }

    public function index()
    {
        $data['title']   = "Manajemen Printer";
        $data['printer'] = $this->Printer_model->get_all();
        $data['divisi']  = $this->Divisi_model->get_all();
        $this->load->view('templates/header', $data);
        $this->load->view('printer/index', $data);
        $this->load->view('templates/footer');
    }
public function simpan()
{
    $data = [
        'divisi'        => $this->input->post('divisi') ?: 0,
        'lokasi_printer'=> $this->input->post('lokasi_printer'),
        'printer_name'  => $this->input->post('printer_name'),
        'port'          => $this->input->post('port'),
        'python_port'   => $this->input->post('python_port'),
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ];
    $this->Printer_model->insert($data);
    redirect('printer');
}

public function update($id)
{
    $data = [
        'divisi'        => $this->input->post('divisi') ?: 0,
        'lokasi_printer'=> $this->input->post('lokasi_printer'),
        'printer_name'  => $this->input->post('printer_name'),
        'port'          => $this->input->post('port'),
        'python_port'   => $this->input->post('python_port'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ];
    $this->Printer_model->update($id, $data);
    redirect('printer');
}


    public function hapus($id)
    {
        $this->Printer_model->delete($id);
        redirect('printer');
    }
public function test($id)
{
    $printer = $this->Printer_model->get_by_id($id);

    if (!$printer) {
        show_error("Printer tidak ditemukan", 404);
    }

    // URL Python berdasarkan port
    $python_url = "http://localhost:" . $printer['python_port'] . "/cetak";

    // Simulasi struk sederhana
    $text = "";
    $text .= "==== TES PRINTER ====\n";
    $text .= "Lokasi : " . strtoupper($printer['lokasi_printer']) . "\n";
    $text .= "Port   : " . $printer['port'] . "\n";
    $text .= "Waktu  : " . date('Y-m-d H:i') . "\n";
    $text .= "------------------------\n";
    $text .= "Produk Dummy x1 - 10000\n";
    $text .= "------------------------\n";
    $text .= "Total : Rp 10.000\n";
    $text .= "========================\n";
    $text .= "Terima Kasih\n\n";

    // Payload ke Python
    $payload = [
        "text" => $text
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $python_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    // Hasil
    echo "<h4>🖨️ Tes Cetak ke {$printer['lokasi_printer']}</h4>";
    echo "<p><strong>Port:</strong> {$printer['port']}<br>";
    echo "<strong>Python Port:</strong> {$printer['python_port']}</p>";
    echo "<hr>";
    echo "<pre>";
    if ($error) {
        echo "❌ Error CURL: $error";
    } else {
        echo htmlentities($response);
    }
    echo "</pre>";
}

// public function test($id)
// {
//     $printer = $this->Printer_model->get_by_id($id);

//     if (!$printer) {
//         show_error("Printer tidak ditemukan", 404);
//     }

//     // URL Python berdasarkan port
//     $python_url = "http://localhost:" . $printer['python_port'] . "/cetak";

//     // Simulasi struk sederhana
// $text = "";
// $text .= "==============================\n";
// $text .= "      BROTHERHOOD COFFEE & CO \n";
// $text .= "==============================\n";
// $text .= "Alamat : Jln Penjawi, Kec. Pati\n";
// $text .= "         Kabupaten Pati\n";
// $text .= "Kasir  : Nadia\n";
// $text .= "Pelanggan: Endah\n";
// $text .= "Waktu  : 2025-04-21 17:30:31\n";
// $text .= "------------------------------\n";
// $text .= "V60 Japanese      x3   90.000\n";
// $text .= "Affogato          x3   90.000\n";
// $text .= "Avocado Juice     x1   25.000\n";
// $text .= "Crispy Chicken    x1   25.000\n";
// $text .= "Mix Platter       x1   50.000\n";
// $text .= "Tahu Cabe Garam   x1   25.000\n";
// $text .= "Air Mineral       x5   30.000\n";
// $text .= "Spring Roll       x1   35.000\n";
// $text .= "Nasi Goreng Broth x4  120.000\n";
// $text .= "Ayam Grg Serundeng x2  60.000\n";
// $text .= "------------------------------\n";
// $text .= "Total             : Rp550.000\n";
// $text .= "PPN 10%           : Rp 55.000\n";
// $text .= "TAGIHAN           : Rp605.000\n";
// $text .= "==============================\n";
// $text .= "     Terima Kasih atas\n";
// $text .= "    Kunjungan Anda!\n";
// $text .= "==============================\n\n";


//     // Payload ke Python
//     $payload = [
//         "text" => $text
//     ];

//     $ch = curl_init();
//     curl_setopt_array($ch, [
//         CURLOPT_URL => $python_url,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_POST => true,
//         CURLOPT_POSTFIELDS => json_encode($payload),
//         CURLOPT_HTTPHEADER => ['Content-Type: application/json']
//     ]);

//     $response = curl_exec($ch);
//     $error = curl_error($ch);
//     curl_close($ch);

//     // Hasil
//     echo "<h4>🖨️ Tes Cetak ke {$printer['lokasi_printer']}</h4>";
//     echo "<p><strong>Port:</strong> {$printer['port']}<br>";
//     echo "<strong>Python Port:</strong> {$printer['python_port']}</p>";
//     echo "<hr>";
//     echo "<pre>";
//     if ($error) {
//         echo "❌ Error CURL: $error";
//     } else {
//         echo htmlentities($response);
//     }
//     echo "</pre>";
// }

}