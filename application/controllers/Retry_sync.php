<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retry_sync extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->database();
    }

    public function index()
    {
        $failed = $this->db->where('status', 'FAILED')->get('pr_sync_log')->result();

        echo "<h3>Sinkronisasi Ulang " . count($failed) . " Data</h3><ul>";

        foreach ($failed as $log) {
            $payload = json_decode($log->data, true);

            // Kirim ulang ke VPS
            $response = $this->Api_model->kirim_data($log->table_name, $payload, false);

            if (isset($response['status']) && $response['status'] === 'success') {
                // ✅ Jika sukses, update log
                $this->db->where('id', $log->id)->update('pr_sync_log', [
                    'status' => 'SENT',
                    'sent_at' => date('Y-m-d H:i:s'),
                    'error_msg' => null
                ]);
                echo "<li>✅ ID {$log->id} - {$log->table_name} berhasil dikirim ulang</li>";
            } else {
                // ❌ Jika gagal, update pesan error
                $error_msg = $response['message'] ?? 'Tidak ada response atau gagal kirim';
                echo "<li>❌ ID {$log->id} - {$log->table_name} gagal lagi. Pesan: $error_msg</li>";

                $this->db->where('id', $log->id)->update('pr_sync_log', [
                    'error_msg' => $error_msg,
                    'sent_at' => null
                ]);

                // Fallback: kirim log gagalnya ke VPS agar tercatat juga di sana
                $this->Api_model->kirim_log_gagal([
                    'table_name' => $log->table_name,
                    'data' => $payload,
                    'status' => 'FAILED',
                    'error_msg' => $error_msg
                ]);
            }
        }

        echo "</ul><hr>Done.";
    }
}