<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
    }

    public function ambil_full() {
        $tables = [
            'pr_voucher',
            'pr_struk_tampilan',
            'pr_struk',
            'pr_produk_extra',
            'pr_produk',
            'pr_printer_setting',
            'pr_printer',
            'pr_poin',
            'pr_pengaturan',
            'pr_metode_pembayaran',
            'pr_lokasi_printer',
            'pr_kategori',
            'pr_jenis_order',
            'pr_extra',
            'pr_divisi',
            'pr_promo_stamp',
            'pr_promo_voucher_auto',
            'pr_produk_paket',
            'pr_produk_paket_detail',

            'pr_customer'
        ];

        $result = [];

        foreach ($tables as $table) {
            $response = $this->Api_model->ambil_data($table);
            
            if ($response && $response['status'] === 'success' && isset($response['data'])) {
                $total_inserted = 0;
                $total_updated = 0;

                foreach ($response['data'] as $row) {
                    if (!isset($row['id'])) continue;

                    $existing = $this->db->get_where($table, ['id' => $row['id']])->row_array();

                    if (!$existing) {
                        // Data baru
                        $this->db->insert($table, $row);
                        $total_inserted++;
                    } else {
                        // Data sudah ada → cek updated_at
                        $vps_updated = strtotime($row['updated_at'] ?? '2000-01-01 00:00:00');
                        $local_updated = strtotime($existing['updated_at'] ?? '2000-01-01 00:00:00');

                        if ($vps_updated > $local_updated) {
                            $this->db->where('id', $row['id'])->update($table, $row);
                            $total_updated++;
                        }
                    }
                }

                $result[$table] = "inserted: $total_inserted, updated: $total_updated";
            } else {
                $result[$table] = '❌ gagal ambil data';
            }
        }

        echo json_encode([
            'status' => 'ok',
            'message' => 'Data umum berhasil disinkronkan.',
            'result' => $result
        ]);
    }



    public function ambil_semua() {
        $tables = [

            'abs_divisi',
            'abs_jabatan',
            'abs_rekening_bank',
            'abs_pegawai',
            'pr_customer',
            'pr_divisi',
            'pr_jenis_order',
            'pr_kategori',
            'pr_lokasi_printer',
            'pr_metode_pembayaran',
            'pr_pengaturan',
            'pr_poin',
            'pr_produk',
            'pr_produk_extra',
            'pr_produk_paket',
            'pr_produk_paket_detail',
            'pr_promo_stamp',
            'pr_promo_voucher_auto',
            'pr_redeem_setting',
            'pr_redeem_log',
            'pr_struk_tampilan',
            'pr_struk',          
            'pr_voucher'
        ];

        $result = [];

        foreach ($tables as $table) {
            $response = $this->Api_model->ambil_data($table);
            
            if ($response && $response['status'] === 'success' && isset($response['data'])) {
                $total_inserted = 0;
                $total_updated = 0;

                foreach ($response['data'] as $row) {
                    if (!isset($row['id'])) continue;

                    $existing = $this->db->get_where($table, ['id' => $row['id']])->row_array();

                    if (!$existing) {
                        // Data baru
                        $this->db->insert($table, $row);
                        $total_inserted++;
                    } else {
                        // Data sudah ada → cek updated_at
                        $vps_updated = strtotime($row['updated_at'] ?? '2000-01-01 00:00:00');
                        $local_updated = strtotime($existing['updated_at'] ?? '2000-01-01 00:00:00');

                        if ($vps_updated > $local_updated) {
                            $this->db->where('id', $row['id'])->update($table, $row);
                            $total_updated++;
                        }
                    }
                }

                $result[$table] = "inserted: $total_inserted, updated: $total_updated";
            } else {
                $result[$table] = '❌ gagal ambil data';
            }
        }

        echo json_encode([
            'status' => 'ok',
            'message' => 'Data umum berhasil disinkronkan.',
            'result' => $result
        ]);
    }


    public function sync_file_uploads()
    {
        $this->load->helper('file');
    
        $api_url = 'https://dashboard.namuacoffee.com/index.php/api_sinkron/daftar_file_uploads';
//        $api_url = 'https://dashboard.namuacoffee.com/api_sinkron/daftar_file_uploads';
        $response = file_get_contents($api_url);
        $data = json_decode($response, true);
    
        if (!$data || $data['status'] !== 'success' || !isset($data['data'])) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal ambil daftar file dari VPS']);
            return;
        }
    
        $base_url_file = 'https://dashboard.namuacoffee.com/';
    
        $downloaded = 0;
        $skipped = 0;
        $failed = 0;
    
        foreach ($data['data'] as $file_info) {
            $remote_file = $file_info['filename'];
            $remote_updated = strtotime($file_info['updated_at'] ?? '2000-01-01 00:00:00');
            $local_path = FCPATH . $remote_file;
    
            // Cek apakah perlu download
            if (file_exists($local_path)) {
                $local_updated = filemtime($local_path);
                if ($local_updated >= $remote_updated) {
                    $skipped++;
                    continue; // tidak perlu download
                }
            }
    
            // Pastikan direktori lokal tersedia
            $dir = dirname($local_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
    
            // Unduh file
            $file_content = @file_get_contents($base_url_file . $remote_file);
            if ($file_content !== false) {
                write_file($local_path, $file_content);
                @touch($local_path, $remote_updated);
                $downloaded++;
            } else {
                $failed++;
            }
        }
    
        echo json_encode([
            'status' => 'ok',
            'message' => 'Sinkronisasi file selesai',
            'result' => [
                'downloaded' => $downloaded,
                'skipped' => $skipped,
                'failed' => $failed,
            ]
        ]);
    }
    
    public function sync_file_uploads_direct()
    {
        $vps_user = 'root';
        $vps_host = '89.116.171.157';
        $remote_path = '/www/wwwroot/dashboard/uploads/';
        $local_path = FCPATH . 'uploads/';
    
        // Pastikan folder local ada
        if (!is_dir($local_path)) {
            mkdir($local_path, 0777, true);
        }
    
        // Command rsync untuk tarik file yang lebih baru
        $cmd = "rsync -avz --update $vps_user@$vps_host:$remote_path $local_path";
    
        // Jalankan
        $output = shell_exec($cmd);
    
        echo json_encode([
            'status' => 'ok',
            'message' => 'Sinkronisasi selesai',
            'log' => $output
        ]);
    }
    

}