<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
    }

    public function ambil_semua() {
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


}