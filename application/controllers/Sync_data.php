<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
    }


    public function index()
{
  $data['title'] = "Sinkronisasi Data Server";
 
  $this->load->view('templates/header', $data);
  $this->load->view('sync_data/index');
  $this->load->view('templates/footer');
}
    
    // semua data tabel
    public function ambil_full() {
        ini_set('max_execution_time', 3000);
        $tables = [
            // Prefix: abs
                'abs_absensi_pending',
                'abs_arsip_gaji',
                'abs_divisi',
                'abs_lokasi_absensi',
                'abs_nilai_lembur',
                'abs_rekening_bank',
                'abs_jabatan',
                'abs_shift',
                'abs_pegawai',
                'abs_rekap_absensi',
                'abs_absensi',
                'abs_deposit',
                'abs_jadwal_shift',
                'abs_kasbon',
                'abs_lembur',
                'abs_potongan',
                'abs_tambahan_lain',
            // Prefix: aset
                'aset_divisi',
                'aset_kategori',
                'aset_data',
                'aset_lampiran',
                'aset_penghapusan',
                'aset_riwayat',
            // Prefix: bl
                'bl_daily_bar',
                'bl_daily_kitchen',
                'bl_divisi',
                'bl_jenis_pengeluaran',
                'bl_kategori',
                'bl_penjualan_majoo',
                'bl_penjualan_produk',
                'bl_produk',
                'bl_rekening',
                'bl_stok_opname',
                'bl_stok_opname_januari',
                'bl_store_request_bar',
                'bl_store_request_kitchen',
                'bl_tipe_produksi',
                'bl_kas',
                'bl_mutasi_kas',
                'bl_mutasi_kas_rekening',
                'bl_refund',
                'bl_rekap_rekening',
                'bl_db_belanja',
                'bl_purchase_bar',
                'bl_purchase_kitchen',
                'bl_purchase_pending',
                'bl_db_purchase',
                'bl_gudang',
//                'bl_gudang_bc 250121',
                'bl_gudang_januari',
                'bl_penyesuaian',
                'bl_persediaan_awal',
                'bl_purchase',
                'bl_stok_penyesuaian',
                'bl_stok_terbuang',
                'bl_store_request',
                'bl_daily_inventory',
            // Prefix: generated
                'generated_tabel',
            // Prefix: kode
                'kode_user',
            // Prefix: lainnya
                'schedule',
                'spbu',
                'users',
                'messages',
            // Prefix: member
                'member_news',
                'member_promo',
            // Prefix: pr
                'pr_base',
                'pr_customer',
                'pr_customer_poin',
                'pr_customer_stamp',
//                'pr_detail_transaksi_paket',
                'pr_divisi',
                'pr_extra',
                'pr_jenis_order',
//                'pr_kasir_shift',
                'pr_lokasi_printer',
                'pr_meja',
                'pr_meja_pembatas',
                'pr_metode_pembayaran',
                'pr_pengaturan',
                'pr_poin',
    //            'pr_printer',
                'pr_printer_setting',
                'pr_produk_extra',
                'pr_promo_stamp',
                'pr_promo_voucher_auto',
                'pr_redeem_log',
                'pr_redeem_setting',
  //              'pr_refund',
                'pr_reservasi',
                'pr_reservasi_detail',
                'pr_stamp_log',
                'pr_struk',
                'pr_struk_tampilan',
//                'pr_sync_log',
 //               'pr_void',
                'pr_voucher',
                'pr_kategori',
                'pr_produk_paket',
                'pr_produksi_base',
                'pr_stok_base',
 //               'pr_kasir_shift_log',
                'pr_reservasi_meja',
                'pr_log_stok_bahan_baku',
                'pr_resep_base',
                'pr_stok_bahan_baku',
                'pr_produk',
 //               'pr_transaksi',
                'pr_produk_paket_detail',
                'pr_resep_produk',
 //               'pr_detail_transaksi',
 //               'pr_pembayaran',
 //               'pr_detail_extra',
                'pr_log_voucher'
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


    // Data POS Kasir
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


    // Data POS Transaksi
    public function ambil_semua() {
        $tables = [

            'pr_customer_poin',
            'pr_customer_stamp',
            'pr_detail_extra',
            'pr_detail_transaksi',
            'pr_detail_transaksi_paket',
            'pr_kasir_shift',
            'pr_kasir_shift_log',
            'pr_log_voucher',
            'pr_pembayaran',
            'pr_refund',
            'pr_poin',
            'pr_produk_paket',
            'pr_produk_paket_detail',
            'pr_promo_stamp',
            'pr_promo_voucher_auto',
            'pr_redeem_log',
            'pr_redeem_setting',
            'pr_stamp_log',
            'pr_void',
            'pr_voucher',
            
            'pr_transaksi'
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

    // Data Produk
    public function ambil_produk() {
        $tables = [

            'pr_produk',
            'pr_produk_extra',
            'pr_produk_paket',
            'pr_produk_paket_detail',
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

    public function ambil_promo() {
        $tables = [
            'member_news',
            'member_promo',
            'pr_poin',
            'pr_promo_stamp',
            'pr_promo_voucher_auto',
            'pr_redeem_log',
            'pr_redeem_setting',
            'pr_stamp_log',
            'pr_voucher',
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



    // semua data Absen
    public function ambil_absen() {
        $tables = [
            'abs_absensi_pending',
            'abs_arsip_gaji',
            'abs_divisi',
            'kode_user',
            'abs_lokasi_absensi',
            'abs_nilai_lembur',
            'abs_rekening_bank',
            'abs_jabatan',
            'abs_shift',
            'abs_pegawai',
            'abs_rekap_absensi',
            'abs_absensi',
            'abs_deposit',
            'abs_jadwal_shift',
            'abs_kasbon',
            'abs_lembur',
            'abs_potongan',
            'abs_tambahan_lain'

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


    // semua Belanja
    public function ambil_belanja() {
        $tables = [
            'bl_daily_bar',
            'bl_daily_kitchen',
            'bl_divisi',
            'bl_jenis_pengeluaran',
            'bl_kategori',
            'bl_penjualan_majoo',
            'bl_penjualan_produk',
            'bl_produk',
            'bl_rekening',
            'bl_stok_opname',
            'bl_stok_opname_januari',
            'bl_store_request_bar',
            'bl_store_request_kitchen',
            'bl_tipe_produksi',
            'bl_kas',
            'bl_mutasi_kas',
            'bl_mutasi_kas_rekening',
            'bl_refund',
            'bl_rekap_rekening',
            'bl_db_belanja',
            'bl_purchase_bar',
            'bl_purchase_kitchen',
            'bl_purchase_pending',
            'bl_db_purchase',
            'bl_gudang',
//            'bl_gudang_bc 250121',
            'bl_gudang_januari',
            'bl_penyesuaian',
            'bl_persediaan_awal',
            'bl_purchase',
            'bl_stok_penyesuaian',
            'bl_stok_terbuang',
            'bl_store_request',
            'bl_daily_inventory'        

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



    // File uploads
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