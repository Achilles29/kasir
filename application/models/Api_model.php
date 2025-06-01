<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

public function __construct()
{
    parent::__construct();
    $this->load->database(); // WAJIB kalau kamu insert ke DB dari model ini
}

  private $api_url = 'https://dashboard.namuacoffee.com/api_sinkron/';
//   private $api_url = 'https://kasir.namuacoffee.com/api_sinkron/';

    
public function kirim_data($table, $data, $log_enabled = true) {

    $this->load->database();

    $payload = json_encode([
        'table' => $table,
        'data' => $data
    ]);

    $ch = curl_init($this->api_url . 'simpan');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    $success = !$curl_error && $http_code === 200;


    if ($log_enabled) {
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as $row) {
                $this->db->insert('pr_sync_log', [
                    'table_name' => $table,
                    'id_table' => $row['id'] ?? null,
                    'data' => json_encode($row),
                    'status' => $success ? 'SENT' : 'FAILED',
                    'error_msg' => $success ? null : ($curl_error ?: 'HTTP: ' . $http_code),
                    'created_at' => date('Y-m-d H:i:s'),
                    'sent_at' => $success ? date('Y-m-d H:i:s') : null
                ]);
            }
        } else {
            $this->db->insert('pr_sync_log', [
                'table_name' => $table,
                'id_table' => $data['id'] ?? null,
                'data' => json_encode($data),
                'status' => $success ? 'SENT' : 'FAILED',
                'error_msg' => $success ? null : ($curl_error ?: 'HTTP: ' . $http_code),
                'created_at' => date('Y-m-d H:i:s'),
                'sent_at' => $success ? date('Y-m-d H:i:s') : null
            ]);
        }
    }


    return json_decode($response, true);
}


    public function kirim_log_gagal($log_data)
    {
        $payload = json_encode($log_data);

        $ch = curl_init($this->api_url . 'simpan_log_gagal');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
    
    public function insert_log_sync($table, $data, $success = true, $error_msg = null)
    {
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as $row) {
                $this->db->insert('pr_sync_log', [
                    'table_name' => $table,
                    'id_table' => $row['id'] ?? null,
                    'data' => json_encode($row),
                    'status' => $success ? 'SENT' : 'FAILED',
                    'error_msg' => $success ? null : $error_msg,
                    'created_at' => date('Y-m-d H:i:s'),
                    'sent_at' => $success ? date('Y-m-d H:i:s') : null
                ]);
            }
        } else {
            $this->db->insert('pr_sync_log', [
                'table_name' => $table,
                'id_table' => $data['id'] ?? null,
                'data' => json_encode($data),
                'status' => $success ? 'SENT' : 'FAILED',
                'error_msg' => $success ? null : $error_msg,
                'created_at' => date('Y-m-d H:i:s'),
                'sent_at' => $success ? date('Y-m-d H:i:s') : null
            ]);
        }
    }
    

public function ambil_data($table) {
    $url = $this->api_url . 'ambil?table=' . urlencode($table);
    $response = @file_get_contents($url);
    if ($response) {
        return json_decode($response, true);
    }
    return ['status' => 'error', 'message' => 'No response'];
}


}