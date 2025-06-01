<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_model extends CI_Model {


        //DI KOMEN UNTUK TES PRODUK PAKET

// public function simpan_transaksi($data_transaksi, $items) {
//     $this->db->trans_begin();

//     $this->db->insert('pr_transaksi', $data_transaksi);
//     $transaksi_id = $this->db->insert_id();

//     if (!$transaksi_id) {
//         $this->db->trans_rollback();
//         return false;
//     }

//     foreach ($items as $item) {
//         $detail_unit_id = uniqid(); // Untuk grup 1 produk

//         for ($i = 0; $i < $item['jumlah']; $i++) {
//             $this->db->insert('pr_detail_transaksi', [
//                 'pr_transaksi_id' => $transaksi_id,
//                 'pr_produk_id' => $item['pr_produk_id'],
//                 'jumlah' => 1,
//                 'harga' => $item['harga'],
//                 'catatan' => $item['catatan'] ?? null,
//                 'status' => null,
//                 'detail_unit_id' => $detail_unit_id,
//                 'is_printed' => 0,
//                 'created_at' => date('Y-m-d H:i:s'),
//             ]);

//             $detail_id = $this->db->insert_id();
            

//             if (!empty($item['extra'])) {
//                 $this->simpan_detail_extra($detail_id, $item['extra']);
//             }
//         }
//     }

//     $this->db->trans_commit();
//     return $transaksi_id;
// }

// public function simpan_transaksi($data_transaksi, $items)
// {
//     $this->db->trans_begin();

//     $this->db->insert('pr_transaksi', $data_transaksi);
//     $transaksi_id = $this->db->insert_id();
//     if (!$transaksi_id) {
//         $this->db->trans_rollback();
//         return false;
//     }

//     foreach ($items as $item) {
//         if (!empty($item['is_paket']) && $item['is_paket'] == 1) {
//             $detail_unit_paket_id = uniqid();

//             // 1. Simpan ke tabel paket
//             $this->db->insert('pr_detail_transaksi_paket', [
//                 'pr_transaksi_id'       => $transaksi_id,
//                 'pr_produk_paket_id'    => $item['pr_produk_paket_id'],
//                 'detail_unit_paket_id'  => $detail_unit_paket_id,
//                 'harga'                 => $item['harga'],
//                 'jumlah'                => $item['jumlah'],
//                 'catatan'               => $item['catatan'] ?? null,
//                 'is_printed'            => 0,
//                 'created_at'            => date('Y-m-d H:i:s'),
//                 'updated_at'            => date('Y-m-d H:i:s')
//             ]);
//             $paket_id = $this->db->insert_id();

//             // 2. Ambil isi produk dari paket
//             $produk_paket = $this->db
//                 ->get_where('pr_produk_paket_detail', ['pr_produk_paket_id' => $item['pr_produk_id']])
//                 ->result();

//             // 3. Ambil nama paket
//             $nama_paket = $this->db->select('nama_paket')
//                 ->where('id', $item['pr_produk_id'])
//                 ->get('pr_produk_paket')->row('nama_paket');

//             // 4. Simpan ke detail transaksi
//             foreach ($produk_paket as $paket_item) {
//                 $detail_unit_id = uniqid(); // Tetap satu grup
            
//                 $total_jumlah = $paket_item->qty * $item['jumlah'];
            
//                 $this->db->insert('pr_detail_transaksi', [
//                     'pr_transaksi_id'               => $transaksi_id,
//                     'pr_produk_id'                  => $paket_item->pr_produk_id,
//                     'jumlah'                        => $total_jumlah, // ✅ dikalikan jumlah paket
//                     'harga'                         => 0,
//                     'catatan'                       => 'Paket: ' . $nama_paket,
//                     'status'                        => null,
//                     'is_printed'                    => 0,
//                     'detail_unit_id'                => $detail_unit_id,
//                     'pr_detail_transaksi_paket_id'  => $paket_id,
//                     'created_at'                    => date('Y-m-d H:i:s'),
//                 ]);
            
//                 $detail_id = $this->db->insert_id();
            
//                 // Simpan extra
//                 if (!empty($item['paket_items'])) {
//                     foreach ($item['paket_items'] as $paket_input) {
//                         if ($paket_input['pr_produk_id'] == $paket_item->pr_produk_id && !empty($paket_input['extra'])) {
//                             $this->simpan_detail_extra($detail_id, $paket_input['extra'], $item['jumlah']); 
//                             // ✅ Kirim jumlah paket juga jika extra perlu dikalikan
//                         }
//                     }
//                 }
//             }
            
//         } else {
//             // Produk biasa
//             $detail_unit_id = uniqid();
//             for ($i = 0; $i < $item['jumlah']; $i++) {
//                 $this->db->insert('pr_detail_transaksi', [
//                     'pr_transaksi_id'    => $transaksi_id,
//                     'pr_produk_id'       => $item['pr_produk_id'],
//                     'jumlah'             => 1,
//                     'harga'              => $item['harga'],
//                     'catatan'            => $item['catatan'] ?? null,
//                     'status'             => null,
//                     'is_printed'         => 0,
//                     'detail_unit_id'     => $detail_unit_id,
//                     'created_at'         => date('Y-m-d H:i:s'),
//                 ]);
//                 $detail_id = $this->db->insert_id();

//                 if (!empty($item['extra'])) {
//                     $this->simpan_detail_extra($detail_id, $item['extra']);
//                 }
//             }
//         }
//     }

//     $this->db->trans_complete();
//     return $transaksi_id;
// }


public function simpan_transaksi($data_transaksi, $items)
{
    $this->db->trans_begin();

    $this->db->insert('pr_transaksi', $data_transaksi);
    $transaksi_id = $this->db->insert_id();
    if (!$transaksi_id) {
        $this->db->trans_rollback();
        return false;
    }

    foreach ($items as $item) {
        if (!empty($item['is_paket']) && $item['is_paket'] == 1) {
            $detail_unit_paket_id = uniqid();

            for ($i = 0; $i < $item['jumlah']; $i++) {
                $this->db->insert('pr_detail_transaksi_paket', [
                    'pr_transaksi_id'       => $transaksi_id,
                    'pr_produk_paket_id'    => $item['pr_produk_paket_id'],
                    'detail_unit_paket_id'  => $detail_unit_paket_id,
                    'harga'                 => $item['harga'],
                    'jumlah'                => 1,
                    'catatan'               => $item['catatan'] ?? null,
                    'is_printed'            => 0,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s')
                ]);
                $paket_id = $this->db->insert_id();

                $produk_paket = $this->db
                    ->get_where('pr_produk_paket_detail', ['pr_produk_paket_id' => $item['pr_produk_id']])
                    ->result();

                $nama_paket = $this->db->select('nama_paket')
                    ->where('id', $item['pr_produk_id'])
                    ->get('pr_produk_paket')->row('nama_paket');

                foreach ($produk_paket as $paket_item) {
                    $detail_unit_id = uniqid();

                    $this->db->insert('pr_detail_transaksi', [
                        'pr_transaksi_id'               => $transaksi_id,
                        'pr_produk_id'                  => $paket_item->pr_produk_id,
                        'jumlah'                        => $paket_item->qty,
                        'harga'                         => 0,
                        'catatan'                       => 'Paket: ' . $nama_paket,
                        'status'                        => null,
                        'is_printed'                    => 0,
                        'detail_unit_id'                => $detail_unit_id,
                        'pr_detail_transaksi_paket_id'  => $paket_id,
                        'created_at'                    => date('Y-m-d H:i:s'),
                    ]);

                    $detail_id = $this->db->insert_id();

                    if (!empty($item['paket_items'])) {
                        foreach ($item['paket_items'] as $paket_input) {
                            if ($paket_input['pr_produk_id'] == $paket_item->pr_produk_id && !empty($paket_input['extra'])) {
                                $this->simpan_detail_extra($detail_id, $paket_input['extra'], 1);
                            }
                        }
                    }
                }
            }
        } else {
            $detail_unit_id = uniqid();
            for ($i = 0; $i < $item['jumlah']; $i++) {
                $this->db->insert('pr_detail_transaksi', [
                    'pr_transaksi_id'    => $transaksi_id,
                    'pr_produk_id'       => $item['pr_produk_id'],
                    'jumlah'             => 1,
                    'harga'              => $item['harga'],
                    'catatan'            => $item['catatan'] ?? null,
                    'status'             => null,
                    'is_printed'         => 0,
                    'detail_unit_id'     => $detail_unit_id,
                    'created_at'         => date('Y-m-d H:i:s'),
                ]);
                $detail_id = $this->db->insert_id();

                if (!empty($item['extra'])) {
                    $this->simpan_detail_extra($detail_id, $item['extra']);
                }
            }
        }
    }

    $this->db->trans_complete();
    return $transaksi_id;
}

public function get_username_by_id($pegawai_id) {
    $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    return $pegawai ? $pegawai->nama : 'Kasir';
}

public function simpan_detail_extra($detail_transaksi_id, $extras, $jumlah_produk = 1)
{
    foreach ($extras as $ex) {
        $jumlah_extra = $jumlah_produk * ($ex['jumlah'] ?? 1); // dikali jumlah produk

        $data = [
            'detail_transaksi_id' => $detail_transaksi_id,
            'pr_produk_extra_id' => $ex['id'],
            'jumlah' => $jumlah_extra,
            'harga' => $ex['harga'],
            'subtotal' => $ex['harga'] * $jumlah_extra,
            'sku' => $ex['sku'] ?? '',
            'satuan' => $ex['satuan'] ?? '',
            'hpp' => $ex['hpp'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('pr_detail_extra', $data);
    }
}



// Update fungsi di Kasir_model.php
public function get_detail_transaksi($transaksi_id, $status = null) {
//    $this->db->select('d.*, t.no_transaksi, p.nama_produk');
    $this->db->select('d.*, t.no_transaksi, p.nama_produk, p.id AS pr_produk_id');
    $this->db->from('pr_detail_transaksi d');
    $this->db->join('pr_transaksi t', 'd.pr_transaksi_id = t.id');
    $this->db->join('pr_produk p', 'd.pr_produk_id = p.id');
    $this->db->where('d.pr_transaksi_id', $transaksi_id);

    if ($status === 'NULL') {
        $this->db->where('d.status IS NULL');
    } elseif ($status) {
        $this->db->where('d.status', $status);
    }

    return $this->db->get()->result_array();
}



// public function update_detail_transaksi($transaksi_id, $items, $transaksi) {
//     $this->db->where('pr_transaksi_id', $transaksi_id)->where('status IS NULL');
//     $existing = $this->db->get('pr_detail_transaksi')->result_array();

//     // Group existing per detail_unit_id
//     $existing_by_unit = [];
//     foreach ($existing as $row) {
//         $existing_by_unit[$row['detail_unit_id']][] = $row;
//     }
    
//     $existing_used = [];

//     foreach ($items as $item) {
//         if (!empty($item['detail_id']) && isset($existing_by_unit[$item['detail_id']])) {
//             $unit_rows = $existing_by_unit[$item['detail_id']];
//             $existing_count = count($unit_rows);
//             $requested = intval($item['jumlah']);

//             // ❗ Jika sudah diprint, abaikan perubahan
//             if (!empty($unit_rows[0]['is_printed']) && $unit_rows[0]['is_printed'] == 1) {
//                 $existing_used[] = $item['detail_id'];
//                 continue;
//             }

//             // ✅ Update jumlah jika sama
//             if ($requested == $existing_count) {
//                 $existing_used[] = $item['detail_id'];

//                 // Update extra & catatan
//                 foreach ($unit_rows as $row) {
//                     $this->db->where('id', $row['id'])->update('pr_detail_transaksi', [
//                         'catatan' => $item['catatan'] ?? null,
//                         'updated_at' => date('Y-m-d H:i:s')
//                     ]);
//                     $this->db->delete('pr_detail_extra', ['detail_transaksi_id' => $row['id']]);

//                     if (!empty($item['extra'])) {
//                         $this->simpan_detail_extra($row['id'], $item['extra']);
//                     }
//                 }

//             } elseif ($requested < $existing_count) {
//                 $existing_used[] = $item['detail_id'];

//                 // Batalkan sisa
//                 $to_cancel = array_slice($unit_rows, $requested);
//                 foreach ($to_cancel as $cancel_row) {
//                     $this->db->where('id', $cancel_row['id'])->update('pr_detail_transaksi', [
//                         'status' => 'BATAL',
//                         'updated_at' => date('Y-m-d H:i:s')
//                     ]);
//                 }

//                 // Update sisa yang tetap
//                 $remain = array_slice($unit_rows, 0, $requested);
//                 foreach ($remain as $row) {
//                     $this->db->where('id', $row['id'])->update('pr_detail_transaksi', [
//                         'catatan' => $item['catatan'] ?? null,
//                         'updated_at' => date('Y-m-d H:i:s')
//                     ]);
//                     $this->db->delete('pr_detail_extra', ['detail_transaksi_id' => $row['id']]);

//                     if (!empty($item['extra'])) {
//                         $this->simpan_detail_extra($row['id'], $item['extra']);
//                     }
//                 }

//             } elseif ($requested > $existing_count) {
//                 $existing_used[] = $item['detail_id'];

//                 // Tambah sisanya
//                 $detail_unit_id = $item['detail_id'];
//                 for ($i = 0; $i < ($requested - $existing_count); $i++) {
//                     $this->db->insert('pr_detail_transaksi', [
//                         'pr_transaksi_id' => $transaksi_id,
//                         'pr_produk_id' => $item['pr_produk_id'],
//                         'jumlah' => 1,
//                         'harga' => $item['harga'],
//                         'catatan' => $item['catatan'] ?? null,
//                         'status' => null,
//                         'detail_unit_id' => $detail_unit_id,
//                         'is_printed' => 0,
//                         'created_at' => date('Y-m-d H:i:s')
//                     ]);

//                     $new_id = $this->db->insert_id();
//                     if (!empty($item['extra'])) {
//                         $this->simpan_detail_extra($new_id, $item['extra']);
//                     }
//                 }
//             }
//         } else {
//             // Produk baru
//             if (!empty($item['detail_id'])) continue; // ❗ Jangan insert ulang produk yang sudah ada

//             $detail_unit_id = uniqid();
//             for ($i = 0; $i < $item['jumlah']; $i++) {
//                 $this->db->insert('pr_detail_transaksi', [
//                     'pr_transaksi_id' => $transaksi_id,
//                     'pr_produk_id' => $item['pr_produk_id'],
//                     'jumlah' => 1,
//                     'harga' => $item['harga'],
//                     'catatan' => $item['catatan'] ?? null,
//                     'status' => null,
//                     'detail_unit_id' => $detail_unit_id,
//                     'is_printed' => 0,
//                     'created_at' => date('Y-m-d H:i:s')
//                 ]);
//                 $new_id = $this->db->insert_id();
//                 if (!empty($item['extra'])) {
//                     $this->simpan_detail_extra($new_id, $item['extra']);
//                 }
//             }
//         }
//     }
    
//     // Hapus baris dengan detail_unit_id yang tidak digunakan, dan belum dicetak
//     foreach ($existing_by_unit as $unit_id => $rows) {
//         if (!in_array($unit_id, $existing_used)) {
//             foreach ($rows as $row) {
//                 if ($row['is_printed'] == 0) {
//                     // Hapus extra terlebih dahulu
//                     $this->db->where('detail_transaksi_id', $row['id'])->delete('pr_detail_extra');
//                     // Hapus detail transaksi
//                     $this->db->where('id', $row['id'])->delete('pr_detail_transaksi');
//                 }
//             }
//         }
//     }

// }


// public function update_detail_transaksi($transaksi_id, $items, $transaksi)
// {
//     foreach ($items as $item) {
        
//         // Hanya proses item baru (bukan hasil edit)
//         if (empty($item['detail_id'])) {

//             // Deteksi apakah item adalah paket valid
//             $is_paket = isset($item['is_paket']) && $item['is_paket'] == 1;
//             $is_valid_paket = $is_paket && !empty($item['pr_produk_id']) && intval($item['pr_produk_id']) > 0;

    
//             if ($is_valid_paket) {
//                 $detail_unit_paket_id = uniqid();
//                 // Simpan ke pr_detail_transaksi_paket
//                 $this->db->insert('pr_detail_transaksi_paket', [
//                     'pr_transaksi_id' => $transaksi_id,
//                     'pr_produk_paket_id' => $item['pr_produk_id'],
//                     'detail_unit_paket_id'  => $detail_unit_paket_id,
//                     'jumlah' => $item['jumlah'],
//                     'harga' => $item['harga'],
//                     'catatan' => $item['catatan'] ?? null,
//                     'is_printed' => 0,
//                     'created_at' => date('Y-m-d H:i:s'),
//                 ]);
//                 $paket_insert_id = $this->db->insert_id();

//                 // Ambil nama paket
//                 $paket_nama = '(Paket Tidak Dikenal)';
//                 $paket_row = $this->db->select('pp.nama_paket')
//                     ->from('pr_detail_transaksi_paket dtp')
//                     ->join('pr_produk_paket pp', 'pp.id = dtp.pr_produk_paket_id')
//                     ->where('dtp.id', $paket_insert_id)
//                     ->get()->row();
//                 if ($paket_row) {
//                     $paket_nama = $paket_row->nama_paket;
//                 }

//                 // Ambil isi paket dari JS atau fallback DB
//                 $paket_items = [];

//                 if (!empty($item['paket_items']) && is_array($item['paket_items'])) {
//                     $paket_items = $item['paket_items'];
//                 } else {
//                     $paket_items_db = $this->db
//                         ->select('d.pr_produk_id, d.qty')
//                         ->from('pr_produk_paket_detail d')
//                         ->join('pr_produk p', 'p.id = d.pr_produk_id', 'inner')
//                         ->where('d.pr_produk_paket_id', $item['pr_produk_id'])
//                         ->get()->result();

//                     foreach ($paket_items_db as $row) {
//                         $paket_items[] = [
//                             'pr_produk_id' => $row->pr_produk_id,
//                             'jumlah' => $row->qty,
//                             'extra' => []
//                         ];
//                     }
//                 }

//                 // Simpan produk isi paket ke pr_detail_transaksi
//                 foreach ($paket_items as $paketItem) {
//                     if (empty($paketItem['pr_produk_id']) || intval($paketItem['pr_produk_id']) <= 0) {
//                         log_message('error', '❌ pr_produk_id isi paket tidak valid: ' . json_encode($paketItem));
//                         continue;
//                     }

//                     $total_qty = $paketItem['jumlah'] * $item['jumlah'];
//                     for ($i = 0; $i < $total_qty; $i++) {
                    
//                     // for ($i = 0; $i  i++) {
//                         $this->db->insert('pr_detail_transaksi', [
//                             'pr_transaksi_id' => $transaksi_id,
//                             'pr_produk_id' => $paketItem['pr_produk_id'],
//                             'jumlah' => 1,
//                             'harga' => 0,
//                             'catatan' => 'Paket: ' . $paket_nama,
//                             'status' => null,
//                             'is_printed' => 0,
//                             'detail_unit_id' => uniqid(),
//                             'pr_detail_transaksi_paket_id' => $paket_insert_id,
//                             'created_at' => date('Y-m-d H:i:s')
//                         ]);

                        
//                         $detail_id = $this->db->insert_id();

//                         // Simpan extra jika ada
//                         if (!empty($paketItem['extra'])) {
//                             $this->simpan_detail_extra($detail_id, $paketItem['extra']);
//                         }
//                     }
//                 }

//             } elseif (!empty($item['pr_produk_id']) && intval($item['pr_produk_id']) > 0) {
//                 // Produk biasa
//                 $detail_unit_id = uniqid();
//                 for ($i = 0; $i < $item['jumlah']; $i++) {
//                     $this->db->insert('pr_detail_transaksi', [
//                         'pr_transaksi_id' => $transaksi_id,
//                         'pr_produk_id' => $item['pr_produk_id'],
//                         'jumlah' => 1,
//                         'harga' => $item['harga'],
//                         'catatan' => $item['catatan'] ?? null,
//                         'status' => null,
//                         'is_printed' => 0,
//                         'detail_unit_id' => $detail_unit_id,
//                         'created_at' => date('Y-m-d H:i:s'),
//                     ]);
//                     $detail_id = $this->db->insert_id();

//                     if (!empty($item['extra'])) {
//                         $this->simpan_detail_extra($detail_id, $item['extra']);
//                     }
//                 }
//             } else {
//                 // Jangan log jika is_paket dan pr_produk_id = undefined → abaikan saja
//                 if (!(isset($item['is_paket']) && $item['is_paket'] == 1 && ($item['pr_produk_id'] === 'undefined' || empty($item['pr_produk_id'])))) {
//                     log_message('error', '❌ Produk tidak valid saat update transaksi: ' . json_encode($item));
//                 }
//             }
            
//         }
//     }
// }

public function update_detail_transaksi($transaksi_id, $items, $transaksi)
{
    foreach ($items as $item) {
        if (empty($item['detail_id'])) {
            $is_paket = isset($item['is_paket']) && $item['is_paket'] == 1;
            $is_valid_paket = $is_paket && !empty($item['pr_produk_id']) && intval($item['pr_produk_id']) > 0;

            if ($is_valid_paket) {
                $detail_unit_paket_id = uniqid();
                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $this->db->insert('pr_detail_transaksi_paket', [
                        'pr_transaksi_id' => $transaksi_id,
                        'pr_produk_paket_id' => $item['pr_produk_id'],
                        'detail_unit_paket_id' => $detail_unit_paket_id,
                        'jumlah' => 1,
                        'harga' => $item['harga'],
                        'catatan' => $item['catatan'] ?? null,
                        'is_printed' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $paket_id = $this->db->insert_id();

                    $paket_nama = $this->db->select('nama_paket')->get_where('pr_produk_paket', ['id' => $item['pr_produk_id']])->row('nama_paket') ?? '(Paket Tidak Dikenal)';

                    $paket_items = !empty($item['paket_items']) && is_array($item['paket_items']) ? $item['paket_items'] : [];
                    if (empty($paket_items)) {
                        $paket_items_db = $this->db
                            ->select('pr_produk_id, qty')
                            ->get_where('pr_produk_paket_detail', ['pr_produk_paket_id' => $item['pr_produk_id']])
                            ->result();

                        foreach ($paket_items_db as $row) {
                            $paket_items[] = [
                                'pr_produk_id' => $row->pr_produk_id,
                                'jumlah' => $row->qty,
                                'extra' => []
                            ];
                        }
                    }

                    foreach ($paket_items as $paketItem) {
                        for ($j = 0; $j < $paketItem['jumlah']; $j++) {
                            $this->db->insert('pr_detail_transaksi', [
                                'pr_transaksi_id' => $transaksi_id,
                                'pr_produk_id' => $paketItem['pr_produk_id'],
                                'jumlah' => 1,
                                'harga' => 0,
                                'catatan' => 'Paket: ' . $paket_nama,
                                'status' => null,
                                'is_printed' => 0,
                                'detail_unit_id' => uniqid(),
                                'pr_detail_transaksi_paket_id' => $paket_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            $detail_id = $this->db->insert_id();

                            if (!empty($paketItem['extra'])) {
                                $this->simpan_detail_extra($detail_id, $paketItem['extra']);
                            }
                        }
                    }
                }
            } elseif (!empty($item['pr_produk_id']) && intval($item['pr_produk_id']) > 0) {
                $detail_unit_id = uniqid();
                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $this->db->insert('pr_detail_transaksi', [
                        'pr_transaksi_id' => $transaksi_id,
                        'pr_produk_id' => $item['pr_produk_id'],
                        'jumlah' => 1,
                        'harga' => $item['harga'],
                        'catatan' => $item['catatan'] ?? null,
                        'status' => null,
                        'is_printed' => 0,
                        'detail_unit_id' => $detail_unit_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $detail_id = $this->db->insert_id();

                    if (!empty($item['extra'])) {
                        $this->simpan_detail_extra($detail_id, $item['extra']);
                    }
                }
            }
        }
    }
}


// public function update_total_transaksi($transaksi_id)
// {
//     // Hitung total produk aktif
//     $this->db->select('SUM(jumlah * harga) AS total_produk');
//     $this->db->from('pr_detail_transaksi');
//     $this->db->where('pr_transaksi_id', $transaksi_id);
//     $this->db->where('(status IS NULL OR status = "")', null, false);
//     $this->db->where('pr_detail_transaksi_paket_id IS NULL'); // ⛔ kecualikan isi paket
//     $produk = $this->db->get()->row();
//     $total_produk = $produk->total_produk ?? 0;

//     // Hitung total extra aktif (perbaikan disini 👇)
//     // $this->db->select('SUM(subtotal) AS total_extra');
//     // $this->db->from('pr_detail_extra');
//     // $this->db->where('detail_transaksi_id IN (SELECT id FROM pr_detail_transaksi WHERE pr_transaksi_id = ' . intval($transaksi_id) . ' AND (status IS NULL OR status = ""))', null, false);


//     $this->db->select('SUM(subtotal) AS total_extra');
//     $this->db->from('pr_detail_extra');
//     $this->db->where('detail_transaksi_id IN (
//         SELECT id FROM pr_detail_transaksi 
//         WHERE pr_transaksi_id = ' . intval($transaksi_id) . ' 
//         AND (status IS NULL OR status = "") 
//         AND pr_detail_transaksi_paket_id IS NULL
//     )', null, false);
    

//     $extra = $this->db->get()->row();
//     $total_extra = $extra->total_extra ?? 0;

//     // Hitung total paket
//     $this->db->select('SUM(harga * jumlah) AS total_paket');
//     $this->db->from('pr_detail_transaksi_paket');
//     $this->db->where('pr_transaksi_id', $transaksi_id);
//     $paket = $this->db->get()->row();
//     $total_paket = $paket->total_paket ?? 0;

//     $total_penjualan = $total_produk + $total_extra + $total_paket;

//     // 🔥 Ambil data transaksi
//     $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();
//     $total_pembayaran = $transaksi->total_pembayaran ?? 0;
//     $diskon = $transaksi->diskon ?? 0;

//     $grand_total = max(0, $total_penjualan - $diskon);
//     $sisa_pembayaran = max(0, $grand_total - $total_pembayaran);

//     // Update ke pr_transaksi
//     $this->db->where('id', $transaksi_id);
//     $this->db->update('pr_transaksi', [
//         'total_penjualan' => $total_penjualan,
//         'sisa_pembayaran' => $sisa_pembayaran
//     ]);
// }

public function update_total_transaksi($transaksi_id)
{
    // Total produk reguler
    $this->db->select('SUM(jumlah * harga) AS total_produk');
    $this->db->from('pr_detail_transaksi');
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('(status IS NULL OR status = "")', null, false);
    $this->db->where('pr_detail_transaksi_paket_id IS NULL');
    $produk = $this->db->get()->row();
    $total_produk = $produk->total_produk ?? 0;

    // Total extra
    $this->db->select('SUM(subtotal) AS total_extra');
    $this->db->from('pr_detail_extra');
    $this->db->where('detail_transaksi_id IN (SELECT id FROM pr_detail_transaksi WHERE pr_transaksi_id = ' . intval($transaksi_id) . ' AND (status IS NULL OR status = ""))', null, false);
    $extra = $this->db->get()->row();
    $total_extra = $extra->total_extra ?? 0;

    // Total paket
    $this->db->select('SUM(harga * jumlah) AS total_paket');
    $this->db->from('pr_detail_transaksi_paket');
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('(status IS NULL OR status = "")', null, false);
    $paket = $this->db->get()->row();
    $total_paket = $paket->total_paket ?? 0;

    $total_penjualan = $total_produk + $total_extra + $total_paket;

    $trx = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();
    $total_pembayaran = $trx->total_pembayaran ?? 0;
    $diskon = $trx->diskon ?? 0;
    $grand_total = max(0, $total_penjualan - $diskon);
    $sisa = max(0, $grand_total - $total_pembayaran);

    $this->db->update('pr_transaksi', [
        'total_penjualan' => $total_penjualan,
        'sisa_pembayaran' => $sisa
    ], ['id' => $transaksi_id]);
}


public function get_transaksi_by_id($id) {
    $this->db->select('t.*, o.jenis_order, 
                       po.username AS kasir_order_username, 
                       pb.username AS kasir_bayar_username');
    $this->db->from('pr_transaksi t');
    $this->db->join('pr_jenis_order o', 't.jenis_order_id = o.id', 'left');
    $this->db->join('abs_pegawai po', 't.kasir_order = po.id', 'left');
    $this->db->join('abs_pegawai pb', 't.kasir_bayar = pb.id', 'left');
    $this->db->where('t.id', $id);
    $transaksi = $this->db->get()->row_array();

    if ($transaksi) {
        $items = $this->db
            ->select('d.*, p.nama_produk, (d.jumlah * d.harga) AS subtotal')
            ->from('pr_detail_transaksi d')
            ->join('pr_produk p', 'd.pr_produk_id = p.id')
            ->where('d.pr_transaksi_id', $id)
            ->get()
            ->result_array();

        // 🔥 Inject extra per item
        foreach ($items as &$item) {
            $extras = $this->db
                ->select('ex.*, pe.nama_extra')
                ->from('pr_detail_extra ex')
                ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
                ->where('ex.detail_transaksi_id', $item['id'])
                ->where('(ex.status IS NULL OR ex.status = "")', null, false)
                ->get()
                ->result_array();

            $item['extra'] = [];
            foreach ($extras as $ex) {
                $item['extra'][] = [
                    'id' => $ex['pr_produk_extra_id'],
                    'harga' => $ex['harga'],
                    'jumlah' => $ex['jumlah'],
                    'satuan' => $ex['satuan'] ?? '',
                    'nama_extra' => $ex['nama_extra'] ?? 'Extra'
                ];
            }
        }
        unset($item);

        $transaksi['items'] = $items;
    }

    return $transaksi;
}

public function group_items($items)
{
    $grouped = [];

    foreach ($items as $item) {
        $detail_unit_id = isset($item['detail_unit_id']) ? $item['detail_unit_id'] : 0;

        if (!isset($grouped[$detail_unit_id])) {
            $grouped[$detail_unit_id] = $item;
        } else {
            $grouped[$detail_unit_id]['jumlah'] += $item['jumlah'];

            // Catatan hanya update kalau kosong
            if (empty($grouped[$detail_unit_id]['catatan']) && !empty($item['catatan'])) {
                $grouped[$detail_unit_id]['catatan'] = $item['catatan'];
            }
        }
    }

    return array_values($grouped);
}


// Di Kasir_model.php
public function get_detail_extra_grouped($transaksi_id)
{
    return $this->db
        ->select('
            de.pr_produk_extra_id, 
            de.harga,
            de.satuan,
            dt.detail_unit_id,
            SUM(de.jumlah) as jumlah_extra,
            pe.nama_extra
        ')
        ->from('pr_detail_extra de')
        ->join('pr_detail_transaksi dt', 'de.detail_transaksi_id = dt.id')
        ->join('pr_produk_extra pe', 'pe.id = de.pr_produk_extra_id', 'left')
        ->where('dt.pr_transaksi_id', $transaksi_id)
        ->group_start()
            ->where('de.status IS NULL')
            ->or_where('de.status', '')
            ->or_where('de.status', 'BERHASIL')
        ->group_end()
//        ->where('(de.status IS NULL OR de.status = "")', null, false)
        ->group_by('dt.detail_unit_id, de.pr_produk_extra_id')
        ->get()
        ->result_array();
}



public function get_pending_orders() {
    return $this->db->select('id, no_transaksi, customer, total_pembayaran')
                    ->from('pr_transaksi')
                    ->where('waktu_bayar IS NULL')
                    ->order_by('waktu_order', 'DESC')
                    ->get()
                    ->result_array();
}

public function get_tampilan_struk($printer_id) {
    return $this->db->get_where('pr_struk_tampilan', ['printer_id' => $printer_id])->row_array();
}

public function generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan) {
    $out = "";
    $width = 32; // Panjang karakter maksimal per baris

    $divisi_id = $printer['divisi'];
    $lokasi = strtoupper($printer['lokasi_printer']);
    $isChecker = ($lokasi == 'CHECKER');
    $isKasir = ($lokasi == 'KASIR');
    
    // Tampilkan judul lokasi printer, kecuali untuk KASIR
    $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';
    if (!$isKasir) {
        $out .= $this->center_text("[ $lokasi ORDER ]", $width) . "\n";
        $out .= str_repeat("-", $width) . "\n";
    }

    // Logo hanya simbolik
    if (!empty($tampilan['show_logo']) && !empty($struk_data['logo'])) {
        $out .= "[LOGO]\n";
    }

    // Outlet, Alamat, Telepon => tengah
    if (!empty($tampilan['show_outlet'])) {
        $out .= $this->center_text(strtoupper($struk_data['nama_outlet']), $width) . "\n";
    }

    if (!empty($tampilan['show_alamat'])) {
        $alamat_lines = explode("\n", wordwrap($struk_data['alamat'], $width));
        foreach ($alamat_lines as $line) {
            $out .= $this->center_text($line, $width) . "\n";
        }
    }

    if (!empty($tampilan['show_no_telepon'])) {
        $out .= $this->center_text("Telp: " . $struk_data['no_telepon'], $width) . "\n";
    }

    // Header tengah
    if (!empty($tampilan['show_custom_header'])) {
        $out .= str_repeat("-", $width) . "\n";
        $lines = explode("\n", wordwrap($struk_data['custom_header'], $width));
        foreach ($lines as $line) {
            $out .= $this->center_text(strtoupper($line), $width) . "\n";
        }
        $out .= str_repeat("-", $width) . "\n";
    }

    $out .= str_repeat("-", $width) . "\n";

    if (!empty($tampilan['show_no_transaksi'])) 
        $out .= "No: " . $transaksi['no_transaksi'] . "\n";

    if (!empty($tampilan['show_kasir_order'])) 
        $out .= "Order: " . ($transaksi['kasir_order_username'] ?? '-') . "\n";

    if (!empty($tampilan['show_kasir_bayar']) && $transaksi['kasir_bayar']) 
        $out .= "Kasir: " . ($transaksi['kasir_bayar_username'] ?? '-') . "\n";


    if (!empty($tampilan['show_customer'])) 
        $out .= "Customer: " . $transaksi['customer'] . "\n";

    if (!empty($tampilan['show_nomor_meja'])) 
        $out .= "Meja: " . $transaksi['nomor_meja'] . "\n";

    if (!empty($tampilan['show_waktu_order'])) 
        $out .= "Order: " . date('d-m-Y H:i', strtotime($transaksi['waktu_order'])) . "\n";

    if (!empty($tampilan['show_waktu_bayar']) && $transaksi['waktu_bayar']) 
        $out .= "Bayar: " . date('d-m-Y H:i', strtotime($transaksi['waktu_bayar'])) . "\n";

    $out .= str_repeat("-", $width) . "\n";

    // $divisi_id = $printer['divisi'];
    // $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';

    // ================= CETAK PRODUK ====================
    $paket_printed = [];
    $paket_ids_to_update = [];

    foreach ($transaksi['items'] as $item) {
        if (!is_null($item['status']) && strtolower($item['status']) == 'batal') continue;

        $produk = $this->db->select('k.pr_divisi_id')->from('pr_produk p')
            ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
            ->where('p.id', $item['pr_produk_id'])->get()->row_array();

        if ($divisi_id && $produk['pr_divisi_id'] != $divisi_id) continue;

        $paket_id = $item['pr_detail_transaksi_paket_id'] ?? null;

        if ($paket_id && !isset($paket_printed[$paket_id])) {
            $paket = $this->db
                ->select('pp.nama_paket, dtp.catatan, dtp.is_printed')
                ->from('pr_detail_transaksi_paket dtp')
                ->join('pr_produk_paket pp', 'pp.id = dtp.pr_produk_paket_id')
                ->where('dtp.id', $paket_id)
                ->get()->row_array();

            if (!$paket || $paket['is_printed'] == 1) continue;

            // Cetak Nama Paket + catatan ke kanan
            $nama_paket = $paket['nama_paket'];
            $catatan = $paket['catatan'] ? " *" . $paket['catatan'] : '';
            $out .= $this->format_struk_line("** $nama_paket", $catatan, $width) . "\n";

            $paket_printed[$paket_id] = true;
            $paket_ids_to_update[] = $paket_id;
        }

        $prefix = $paket_id ? '  > ' : '';
        $line_left = "{$prefix}{$item['jumlah']}x {$item['nama_produk']}";
        $line_right = $isKasir ? number_format($item['harga'] * $item['jumlah'], 0, ',', '.') : '';
        $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";

        if (!empty($item['extra'])) {
            foreach ($item['extra'] as $ex) {
                $nama_extra = $ex['nama_extra'] ?? 'Extra';
                $line_left = "     > {$ex['jumlah']}x {$nama_extra}";
                $line_right = $isKasir ? number_format($ex['harga'] * $ex['jumlah'], 0, ',', '.') : '';
                $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
            }
        }

        if (!empty($item['catatan']) && !$paket_id) {
            foreach (explode("\n", wordwrap("- " . $item['catatan'], $width - 2)) as $line) {
                $out .= "  $line\n";
            }
        }
    }

    $out .= str_repeat("-", $width) . "\n";

    
    // === RINGKASAN PEMBAYARAN ===
    if ($isKasir) {
        // $out .= str_repeat("-", $width) . "\n";

        // Subtotal Produk (tanpa diskon)
        $subtotal_produk = $transaksi['total_penjualan'];
        $out .= $this->format_struk_line('Subtotal Produk', number_format($subtotal_produk, 0, ',', '.'), $width) . "\n";

        // Diskon
        $diskon = intval($transaksi['diskon'] ?? 0);
        $out .= $this->format_struk_line('Diskon', number_format($diskon, 0, ',', '.'), $width) . "\n";

        // Total Tagihan
        $total_tagihan = max(0, $subtotal_produk - $diskon);
        $out .= $this->format_struk_line('Total Tagihan', number_format($total_tagihan, 0, ',', '.'), $width) . "\n";

        $out .= str_repeat("-", $width) . "\n";

        // Total Bayar
        $total_bayar = intval($transaksi['total_pembayaran'] ?? 0);
        $out .= $this->format_struk_line('Total Bayar', number_format($total_bayar, 0, ',', '.'), $width) . "\n";
    }
    if (!empty($tampilan['show_custom_footer'])) {
        $lines = explode("\n", wordwrap($struk_data['custom_footer'], $width));
        foreach ($lines as $line) {
            $out .= $this->center_text(strtoupper($line), $width) . "\n";
        }
    }

    // Tampilkan promo voucher otomatis jika ada
    if ($isKasir && !empty($transaksi['voucher_otomatis'])) {
        $out .= str_repeat("-", $width) . "\n";
        $out .= $this->center_text("🎁 VOUCHER PROMO 🎁", $width) . "\n";
        $out .= str_repeat("-", $width) . "\n";

        foreach ($transaksi['voucher_otomatis'] as $v) {
            $nilai_label = $v['jenis'] === 'persentase'
                ? "{$v['nilai']}%"
                : "Rp " . number_format($v['nilai'], 0, ',', '.');

            $out .= $this->wrap_text("Selamat anda mendapatkan Voucher senilai $nilai_label", $width) . "\n";
            $out .= $this->wrap_text("untuk transaksi berikutnya dengan Kode Voucher: {$v['kode_voucher']}", $width) . "\n";

            if (empty($transaksi['customer_id'])) {
                $out .= $this->wrap_text("Cukup dengan daftarkan diri anda sebagai member namuacoffee", $width) . "\n";
            }

            $out .= $this->wrap_text("Gunakan sebelum tanggal " . date('d M Y', strtotime($v['tanggal_berakhir'])), $width) . "\n";
            $out .= "*S&K Berlaku\n";
            $out .= str_repeat("-", $width) . "\n";
        }
    }
    return $out;
}

// generate awal
// public function generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan) {
//     $out = "";
//     $width = 32; // Panjang karakter maksimal per baris

//     $divisi_id = $printer['divisi'];
//     $lokasi = strtoupper($printer['lokasi_printer']);
//     $isChecker = ($lokasi == 'CHECKER');
//     $isKasir = ($lokasi == 'KASIR');
    
//     // Tampilkan judul lokasi printer, kecuali untuk KASIR
//     $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';
//     if (!$isKasir) {
//         $out .= $this->center_text("[ $lokasi ORDER ]", $width) . "\n";
//         $out .= str_repeat("-", $width) . "\n";
//     }

//     // Logo hanya simbolik
//     if (!empty($tampilan['show_logo']) && !empty($struk_data['logo'])) {
//         $out .= "[LOGO]\n";
//     }

//     // Outlet, Alamat, Telepon => tengah
//     if (!empty($tampilan['show_outlet'])) {
//         $out .= $this->center_text(strtoupper($struk_data['nama_outlet']), $width) . "\n";
//     }

//     if (!empty($tampilan['show_alamat'])) {
//         $alamat_lines = explode("\n", wordwrap($struk_data['alamat'], $width));
//         foreach ($alamat_lines as $line) {
//             $out .= $this->center_text($line, $width) . "\n";
//         }
//     }

//     if (!empty($tampilan['show_no_telepon'])) {
//         $out .= $this->center_text("Telp: " . $struk_data['no_telepon'], $width) . "\n";
//     }

//     // Header tengah
//     if (!empty($tampilan['show_custom_header'])) {
//         $out .= str_repeat("-", $width) . "\n";
//         $lines = explode("\n", wordwrap($struk_data['custom_header'], $width));
//         foreach ($lines as $line) {
//             $out .= $this->center_text(strtoupper($line), $width) . "\n";
//         }
//         $out .= str_repeat("-", $width) . "\n";
//     }

//     $out .= str_repeat("-", $width) . "\n";

//     if (!empty($tampilan['show_no_transaksi'])) 
//         $out .= "No: " . $transaksi['no_transaksi'] . "\n";

//     if (!empty($tampilan['show_kasir_order'])) 
//         $out .= "Order: " . ($transaksi['kasir_order_username'] ?? '-') . "\n";

//     if (!empty($tampilan['show_kasir_bayar']) && $transaksi['kasir_bayar']) 
//         $out .= "Kasir: " . ($transaksi['kasir_bayar_username'] ?? '-') . "\n";


//     if (!empty($tampilan['show_customer'])) 
//         $out .= "Customer: " . $transaksi['customer'] . "\n";

//     if (!empty($tampilan['show_nomor_meja'])) 
//         $out .= "Meja: " . $transaksi['nomor_meja'] . "\n";

//     if (!empty($tampilan['show_waktu_order'])) 
//         $out .= "Order: " . date('d-m-Y H:i', strtotime($transaksi['waktu_order'])) . "\n";

//     if (!empty($tampilan['show_waktu_bayar']) && $transaksi['waktu_bayar']) 
//         $out .= "Bayar: " . date('d-m-Y H:i', strtotime($transaksi['waktu_bayar'])) . "\n";

//     $out .= str_repeat("-", $width) . "\n";

//     $divisi_id = $printer['divisi'];
//     $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';

//     foreach ($transaksi['items'] as $item) {
//         if (!is_null($item['status']) && strtolower($item['status']) == 'batal') {
//             continue;
//         }

//         $produk = $this->db->select('k.pr_divisi_id')->from('pr_produk p')
//             ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
//             ->where('p.id', $item['pr_produk_id'])->get()->row_array();

//         if ($divisi_id && $produk['pr_divisi_id'] != $divisi_id) continue;

//         $line_left = "{$item['jumlah']}x {$item['nama_produk']}";
//         $line_right = $isKasir ? number_format($item['harga'] * $item['jumlah'], 0, ',', '.') : '';
//         $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";

//         // Extra langsung dari $item['extra'], BUKAN query database lagi!
//         if (!empty($item['extra'])) {
//             foreach ($item['extra'] as $ex) {
//         //        $nama_extra = $ex['nama_extra'] ?? $ex['nama'] ?? 'Extra';
//                 $nama_extra = $ex['nama_extra'] ?? 'Extra';
//                 $line_left = "  > {$ex['jumlah']}x {$nama_extra}";
//                 $line_right = $isKasir ? number_format($ex['harga'] * $ex['jumlah'], 0, ',', '.') : '';
//                 $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
//             }

//         }

//         // Note
//         if (!empty($item['catatan'])) {
//             $note_lines = explode("\n", wordwrap("- " . $item['catatan'], $width - 2));
//             foreach ($note_lines as $line) {
//                 $out .= "  $line\n";
//             }
//         }
//     }



//     $out .= str_repeat("-", $width) . "\n";

//     if ($isKasir) {
//         // $out .= str_repeat("-", $width) . "\n";

//         // Subtotal Produk (tanpa diskon)
//         $subtotal_produk = $transaksi['total_penjualan'];
//         $out .= $this->format_struk_line('Subtotal Produk', number_format($subtotal_produk, 0, ',', '.'), $width) . "\n";

//         // Diskon
//         $diskon = intval($transaksi['diskon'] ?? 0);
//         $out .= $this->format_struk_line('Diskon', number_format($diskon, 0, ',', '.'), $width) . "\n";

//         // Total Tagihan
//         $total_tagihan = max(0, $subtotal_produk - $diskon);
//         $out .= $this->format_struk_line('Total Tagihan', number_format($total_tagihan, 0, ',', '.'), $width) . "\n";

//         $out .= str_repeat("-", $width) . "\n";

//         // Total Bayar
//         $total_bayar = intval($transaksi['total_pembayaran'] ?? 0);
//         $out .= $this->format_struk_line('Total Bayar', number_format($total_bayar, 0, ',', '.'), $width) . "\n";
//     }
//     if (!empty($tampilan['show_custom_footer'])) {
//         $lines = explode("\n", wordwrap($struk_data['custom_footer'], $width));
//         foreach ($lines as $line) {
//             $out .= $this->center_text(strtoupper($line), $width) . "\n";
//         }
//     }

//     // Tampilkan promo voucher otomatis jika ada
//     if ($isKasir && !empty($transaksi['voucher_otomatis'])) {
//         $out .= str_repeat("-", $width) . "\n";
//         $out .= $this->center_text("🎁 VOUCHER PROMO 🎁", $width) . "\n";
//         $out .= str_repeat("-", $width) . "\n";

//         foreach ($transaksi['voucher_otomatis'] as $v) {
//             $nilai_label = $v['jenis'] === 'persentase'
//                 ? "{$v['nilai']}%"
//                 : "Rp " . number_format($v['nilai'], 0, ',', '.');

//             $out .= $this->wrap_text("Selamat anda mendapatkan Voucher senilai $nilai_label", $width) . "\n";
//             $out .= $this->wrap_text("untuk transaksi berikutnya dengan Kode Voucher: {$v['kode_voucher']}", $width) . "\n";

//             if (empty($transaksi['customer_id'])) {
//                 $out .= $this->wrap_text("Cukup dengan daftarkan diri anda sebagai member namuacoffee", $width) . "\n";
//             }

//             $out .= $this->wrap_text("Gunakan sebelum tanggal " . date('d M Y', strtotime($v['tanggal_berakhir'])), $width) . "\n";
//             $out .= "*S&K Berlaku\n";
//             $out .= str_repeat("-", $width) . "\n";
//         }
//     }



//     return $out;
// }

// public function generate_struk_void($voids, $struk_data, $tampilan, $lokasi)
// {
//     $out = "";
//     $width = 32;

//     $out .= str_repeat("=", $width) . "\n";
//     $out .= $this->center_text("VOID ORDER", $width) . "\n";
//     $out .= str_repeat("=", $width) . "\n";

//     $out .= $this->center_text(strtoupper($lokasi), $width) . "\n";
//     $out .= str_repeat("-", $width) . "\n";

//     foreach ($voids as $v) {
//         $qty = $v['jumlah'];
//         $nama = $v['nama_produk'] ?? $v['nama_extra'];
//         $out .= "- {$qty}x {$nama}\n";
//     }

//     $out .= str_repeat("-", $width) . "\n";
//     $out .= "Alasan: " . ($voids[0]['alasan'] ?? '-') . "\n";
//     $out .= "Void by: " . $this->get_username_by_id($voids[0]['void_by']) . "\n";
//     $out .= "Waktu: " . date('d-m-Y H:i', strtotime($voids[0]['waktu'])) . "\n";
//     $out .= str_repeat("=", $width) . "\n";

//     return $out;
// }



private function wrap_text_line($text, $width = 32)
{
    $lines = explode("\n", wordwrap($text, $width));
    return implode("\n", $lines);
}
public function wrap_text($text, $width)
{
    return wordwrap($text, $width, "\n", true);
}


private function format_struk_line($left, $right, $width = 32) {
    $left = trim($left);
    $right = trim($right);

    $maxLeftWidth = $width - strlen($right) - 1; // Sisakan ruang untuk right + 1 spasi

    if (strlen($left) > $maxLeftWidth) {
        $lines = wordwrap($left, $maxLeftWidth, "\n", true);
        $lines = explode("\n", $lines);
        $output = '';

        foreach ($lines as $i => $line) {
            if ($i == 0) {
                $space = $width - strlen($line) - strlen($right);
                $output .= $line . str_repeat(' ', max(1, $space)) . $right;
            } else {
                $output .= "\n" . $line; // baris selanjutnya tidak ada right
            }
        }

        return $output;
    } else {
        $space = $width - strlen($left) - strlen($right);
        return $left . str_repeat(' ', max(1, $space)) . $right;
    }
}

private function center_text($text, $width = 32) {
    $text = trim($text);
    $padding = floor(($width - strlen($text)) / 2);
    return str_repeat(' ', max(0, $padding)) . $text;
}



public function generate_kode_void()
{
    $prefix = 'V' . date('ymd'); // V240430
    $today = date('Y-m-d');

    // Hitung jumlah void hari ini
    $jumlah = $this->db
        ->where('DATE(created_at)', $today)
        ->count_all_results('pr_void');

    // Increment
    $urut = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);

    return $prefix . $urut; // Hasil: V240430001
}


public function void_batch($items, $alasan)
{
    $user_id = $this->session->userdata('pegawai_id');
    $now = date('Y-m-d H:i:s');
    $kode_void = $this->generate_kode_void();

    $this->db->trans_start();
    $new_void_ids = [];
    $transaksi_id_terakhir = null;

    foreach ($items as $item) {
        log_message('debug', 'VOID ITEM: ' . json_encode($item));
        if (!isset($item['type'])) {
            continue;
        }

        if ($item['type'] === 'produk') {
            $produk = $this->db->get_where('pr_detail_transaksi', ['id' => $item['id']])->row();
            if ($produk) {
                $transaksi_id_terakhir = $produk->pr_transaksi_id;

                $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id_terakhir])->row();
                $master_produk = $this->db->get_where('pr_produk', ['id' => $produk->pr_produk_id])->row();

                $this->db->where('id', $item['id'])->update('pr_detail_transaksi', ['status' => 'BATAL']);
                $this->db->set('total_penjualan', 'total_penjualan - ' . ($produk->harga * $produk->jumlah), false);
                $this->db->set('sisa_pembayaran', 'GREATEST(0, sisa_pembayaran - ' . ($produk->harga * $produk->jumlah) . ')', false);
                $this->db->where('id', $transaksi_id_terakhir)->update('pr_transaksi');

                $this->db->insert('pr_void', [
                    'pr_transaksi_id'     => $transaksi_id_terakhir,
                    'kode_void'           => $kode_void,
                    'no_transaksi'        => $transaksi->no_transaksi,
                    'detail_transaksi_id' => $produk->id,
                    'nama_produk'         => $master_produk ? $master_produk->nama_produk : $produk->nama_produk,
                    'pr_produk_id'        => $produk->pr_produk_id,
                    'jumlah'              => $produk->jumlah,
                    'harga'               => $produk->harga,
                    'catatan'             => $produk->catatan,
                    'alasan'              => $alasan,
                    'void_by'             => $user_id,
                    'waktu'               => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);

                $new_void_ids[] = $this->db->insert_id();
            }
        } elseif ($item['type'] === 'extra') {
            $extra = $this->db->get_where('pr_detail_extra', ['id' => $item['id']])->row();
            if ($extra) {
                $produk = $this->db->get_where('pr_detail_transaksi', ['id' => $extra->detail_transaksi_id])->row();
                $transaksi_id_terakhir = $produk->pr_transaksi_id;

                $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id_terakhir])->row();
                $master_produk = $this->db->get_where('pr_produk', ['id' => $produk->pr_produk_id])->row();
                $extra_nama = $this->db->get_where('pr_produk_extra', ['id' => $extra->pr_produk_extra_id])->row();

                $this->db->where('id', $item['id'])->update('pr_detail_extra', ['status' => 'BATAL']);
                $this->db->set('total_penjualan', 'total_penjualan - ' . ($extra->harga * $extra->jumlah), false);
                $this->db->set('sisa_pembayaran', 'GREATEST(0, sisa_pembayaran - ' . ($extra->harga * $extra->jumlah) . ')', false);
                $this->db->where('id', $transaksi_id_terakhir)->update('pr_transaksi');

                $this->db->insert('pr_void', [
                    'pr_transaksi_id'     => $transaksi_id_terakhir,
                    'kode_void'           => $kode_void,
                    'no_transaksi'        => $transaksi->no_transaksi,
                    'detail_transaksi_id' => $produk->id,
                    'nama_produk'         => $master_produk ? $master_produk->nama_produk : 'Produk Tidak Dikenal',
                    'pr_produk_id'        => $produk->pr_produk_id,
                    'detail_extra_id'     => $extra->id,
                    'produk_extra_id'     => $extra->pr_produk_extra_id,
                    'nama_extra'          => $extra_nama ? $extra_nama->nama_extra : 'Extra Tidak Dikenal',
                    'jumlah'              => $extra->jumlah,
                    'harga'               => $extra->harga,
                    'catatan'             => 'Extra',
                    'alasan'              => $alasan,
                    'void_by'             => $user_id,
                    'waktu'               => $now,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);

                $new_void_ids[] = $this->db->insert_id();
            }
        }

        elseif ($item['type'] === 'paket') {
            $paket = $this->db->get_where('pr_detail_transaksi_paket', ['id' => $item['id']])->row();
            if ($paket) {
                $transaksi_id_terakhir = $paket->pr_transaksi_id;
                $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id_terakhir])->row();
        
                // Update status pr_detail_transaksi_paket
                $this->db->where('id', $item['id'])->update('pr_detail_transaksi_paket', ['status' => 'BATAL']);
        
                // Update total penjualan dan sisa pembayaran
                $this->db->set('total_penjualan', 'total_penjualan - ' . ($paket->harga * $paket->jumlah), false);
                $this->db->set('sisa_pembayaran', 'GREATEST(0, sisa_pembayaran - ' . ($paket->harga * $paket->jumlah) . ')', false);
                $this->db->where('id', $transaksi_id_terakhir)->update('pr_transaksi');
        
                // Ambil nama paket
                $master_paket = $this->db->get_where('pr_produk', ['id' => $paket->pr_produk_paket_id])->row();
        
                // === Baris 1: Paket sebagai header ===
                $this->db->insert('pr_void', [
                    'pr_transaksi_id'              => $transaksi_id_terakhir,
                    'kode_void'                    => $kode_void,
                    'no_transaksi'                 => $transaksi->no_transaksi,
                    'detail_transaksi_id'          => null,
                    'detail_transaksi_paket_id'    => $paket->id,
                    'nama_produk'                  => null,
                    'pr_produk_id'                 => null,
                    'jumlah'                       => $paket->jumlah,
                    'harga'                        => $paket->harga,
                    'catatan'                      => $paket->catatan ?? 'Paket',
                    'alasan'                       => $alasan,
                    'void_by'                      => $user_id,
                    'waktu'                        => $now,
                    'created_at'                   => $now,
                    'updated_at'                   => $now,
                ]);
        
                $new_void_ids[] = $this->db->insert_id();
        
                // === Baris 2+: Produk dalam paket ===
                $detail_items = $this->db->get_where('pr_detail_transaksi', [
                    'pr_detail_transaksi_paket_id' => $paket->id
                ])->result();
        
                foreach ($detail_items as $produk) {
                    $master_produk = $this->db->get_where('pr_produk', ['id' => $produk->pr_produk_id])->row();
        
                    // Tandai produk sebagai BATAL
                    $this->db->where('id', $produk->id)->update('pr_detail_transaksi', ['status' => 'BATAL']);
        
                    $this->db->insert('pr_void', [
                        'pr_transaksi_id'              => $transaksi_id_terakhir,
                        'kode_void'                    => $kode_void,
                        'no_transaksi'                 => $transaksi->no_transaksi,
                        'detail_transaksi_id'          => $produk->id,
                        'detail_transaksi_paket_id'    => $paket->id,
                        'nama_produk'                  => $master_produk ? $master_produk->nama_produk : 'Produk Paket',
                        'pr_produk_id'                 => $produk->pr_produk_id,
                        'jumlah'                       => $produk->jumlah,
                        'harga'                        => 0,
                        'catatan'                      => 'Item Paket',
                        'alasan'                       => $alasan,
                        'void_by'                      => $user_id,
                        'waktu'                        => $now,
                        'created_at'                   => $now,
                        'updated_at'                   => $now,
                    ]);
        
                    $new_void_ids[] = $this->db->insert_id();
                }
            }
        }
        

    }

    // ✅ Cek apakah semua item dalam transaksi sudah di-void
    if ($transaksi_id_terakhir) {
        $sisa_produk = $this->db
            ->where('pr_transaksi_id', $transaksi_id_terakhir)
            ->where('status IS NULL', null, false)
            ->count_all_results('pr_detail_transaksi');

        $sisa_extra = $this->db
            ->join('pr_detail_transaksi dt', 'dt.id = pr_detail_extra.detail_transaksi_id')
            ->where('dt.pr_transaksi_id', $transaksi_id_terakhir)
            ->where('pr_detail_extra.status IS NULL', null, false)
            ->count_all_results('pr_detail_extra');

        if ($sisa_produk == 0 && $sisa_extra == 0) {
            $this->db->where('id', $transaksi_id_terakhir)->update('pr_transaksi', [
                'status_pembayaran' => 'BATAL'
            ]);
        }
    }

    $this->db->trans_complete();
    
        // 🔥 Sinkronisasi ke VPS
        if ($this->db->trans_status()) {
            $this->load->model('Api_model');
        
            // Ambil ulang data untuk dikirim
            $void_data = [];
            if (!empty($new_void_ids)) {
                $void_data = $this->db->where_in('id', $new_void_ids)->get('pr_void')->result_array();
            }
        
            $transaksi_data = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id_terakhir])->row_array();
        
            $detail_data = $this->db
                ->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id_terakhir])
                ->result_array();
        
            $extra_data = $this->db
                ->select('e.*')
                ->from('pr_detail_extra e')
                ->join('pr_detail_transaksi dt', 'dt.id = e.detail_transaksi_id')
                ->where('dt.pr_transaksi_id', $transaksi_id_terakhir)
                ->get()
                ->result_array();
        
            $paket_data = $this->db
                ->where('pr_transaksi_id', $transaksi_id_terakhir)
                ->get('pr_detail_transaksi_paket')
                ->result_array();
        
            if (!empty($void_data)) {
                $this->Api_model->kirim_data('pr_void', $void_data);
                $this->Api_model->insert_log_sync('pr_void', $void_data);
            }
        
            if (!empty($paket_data)) {
                $this->Api_model->kirim_data('pr_detail_transaksi_paket', $paket_data);
                $this->Api_model->insert_log_sync('pr_detail_transaksi_paket', $paket_data);
            }
        
            if (!empty($transaksi_data)) {
                $this->Api_model->kirim_data('pr_transaksi', $transaksi_data);
                $this->Api_model->insert_log_sync('pr_transaksi', $transaksi_data);
            }
        
            if (!empty($detail_data)) {
                $this->Api_model->kirim_data('pr_detail_transaksi', $detail_data);
                $this->Api_model->insert_log_sync('pr_detail_transaksi', $detail_data);
            }
        
            if (!empty($extra_data)) {
                $this->Api_model->kirim_data('pr_detail_extra', $extra_data);
                $this->Api_model->insert_log_sync('pr_detail_extra', $extra_data);
            }
        }
        

    return $new_void_ids;
}

// public function generate_void_struk($transaksi, $produk_void, $printer, $struk_data, $lokasi)

// {
//     $out = '';
//     $width = 32; // atau dari printer setting kalau mau dinamis
//     $waktu_void = date('d-m-Y H:i'); // waktu saat ini

//     if (empty($produk_void)) {
//         return '';
//     }

//     $no_transaksi = $transaksi['no_transaksi'] ?? '-';
//     $customer = $transaksi['customer'] ?? '-';
//     $nomor_meja = $transaksi['nomor_meja'] ?? '-';
//     $kasir_order = $transaksi['kasir_order'] ?? '-';
//     $alasan_void = $produk_void[0]['alasan'] ?? '-'; // alasan tetap ambil dari produk_void


//     $divisi_id = $printer['divisi'];
//     $lokasi = strtoupper($printer['lokasi_printer']);
//     $isChecker = ($lokasi == 'CHECKER');

    
//     // --- Tambahkan Judul [KITCHEN ORDER], dst ---
//     $out .= $this->center_text("[ $lokasi VOID ]", $width) . "\n";
//     $out .= str_repeat("-", $width) . "\n";

//     $out .= "No: " . $no_transaksi . "\n";
//     $out .= "Order: " . $kasir_order . "\n";
//     $out .= "Customer: " . $customer . "\n";
//     $out .= "Meja: " . $nomor_meja . "\n";
//     $out .= "Void: " . $waktu_void . "\n";

//     $out .= str_repeat("-", $width) . "\n";

//     // --- Item Void ---
//     foreach ($produk_void as $item) {
//         $jumlah = intval($item['jumlah']);
//         $harga = intval($item['harga']);

//         //TAMBAHAN VOID PAKET
//         $line_left = "{$jumlah}x ";
//         $line_left .= !empty($item['nama_extra']) ? "> {$item['nama_extra']}" : "{$item['nama_produk']}";


//         if (!empty($item['nama_extra'])) {
//             $line_left = "> {$jumlah}x {$item['nama_extra']}";
//         } else {
//             $line_left = "{$jumlah}x {$item['nama_produk']}";
//         }

//         $line_right = number_format($harga, 0, ',', '.');
//         $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
//     }

//     $out .= str_repeat("-", $width) . "\n";
//     $out .= "Alasan: " . $alasan_void . "\n";
//     $out .= str_repeat("-", $width) . "\n";
//     $out .= date('d/m/Y H:i:s') . "\n";

//     return $out;
// }

public function generate_void_struk($transaksi, $produk_void, $printer, $struk_data, $lokasi)
{
    $out = '';
    $width = 32;
    $waktu_void = date('d-m-Y H:i');

    if (empty($produk_void)) return '';

    $no_transaksi = $transaksi['no_transaksi'] ?? '-';
    $customer = $transaksi['customer'] ?? '-';
    $nomor_meja = $transaksi['nomor_meja'] ?? '-';
    $kasir_order = $transaksi['kasir_order'] ?? '-';
    $alasan_void = $produk_void[0]['alasan'] ?? '-';

    $lokasi = strtoupper($printer['lokasi_printer']);

    // === HEADER ===
    $out .= $this->center_text("[ $lokasi VOID ]", $width) . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= "No: " . $no_transaksi . "\n";
    $out .= "Order: " . $kasir_order . "\n";
    $out .= "Customer: " . $customer . "\n";
    $out .= "Meja: " . $nomor_meja . "\n";
    $out .= "Void: " . $waktu_void . "\n";
    $out .= str_repeat("-", $width) . "\n";

// === GROUP PRODUK PAKET DAN NON-PAKET ===
$paket_group = [];
$non_paket = [];

foreach ($produk_void as $item) {
    $jumlah = intval($item['jumlah'] ?? 0);
    $harga = intval($item['harga'] ?? 0);

    // Cek apakah extra
    if (!empty($item['nama_extra'])) {
        $non_paket[] = [
            'type' => 'extra',
            'nama' => $item['nama_extra'],
            'jumlah' => $jumlah,
            'harga' => $harga
        ];
        continue;
    }

    // Jika produk bagian dari paket
    if (!empty($item['detail_transaksi_paket_id'])) {
        $paket_id = $item['detail_transaksi_paket_id'];

        // Ambil nama paket dari DB hanya jika belum tersimpan
        if (!isset($paket_group[$paket_id])) {
            $nama_paket = $this->db
                ->select('pp.nama_paket')
                ->from('pr_detail_transaksi_paket dp')
                ->join('pr_produk_paket pp', 'pp.id = dp.pr_produk_paket_id')
                ->where('dp.id', $paket_id)
                ->get()->row('nama_paket') ?? 'Paket';

            $paket_group[$paket_id] = [
                'nama_paket' => $nama_paket,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'isi' => []
            ];
        }

        // Tambahkan item ke isi paket
        $paket_group[$paket_id]['isi'][] = [
            'nama' => $item['nama_produk'],
            'jumlah' => $jumlah
        ];
    } else {
        // Produk reguler
        $non_paket[] = [
            'type' => 'produk',
            'nama' => $item['nama_produk'],
            'jumlah' => $jumlah,
            'harga' => $harga
        ];
    }
}


    // === CETAK PRODUK PAKET ===
    foreach ($paket_group as $paket) {
        $nama_paket = $paket['nama_paket'] ?? 'Paket';
        $jumlah = $paket['jumlah'] ?? 1;
    
        // ✅ Format: 1x Paket : Nama Paket
        $line_left = "{$jumlah}x Paket : {$nama_paket}";
        $out .= $line_left . "\n";
    
        // Cetak isi paket
        foreach ($paket['isi'] as $isi) {
            $out .= "  * {$isi['nama']} (x{$isi['jumlah']})\n";
        }
    
        $out .= str_repeat("-", $width) . "\n";
    }
    

    // === CETAK PRODUK REGULER DAN EXTRA ===
    foreach ($non_paket as $np) {
        $nama = $np['nama'] ?? '-';
        $jumlah = intval($np['jumlah'] ?? 1);
        $harga = intval($np['harga'] ?? 0);

        if ($np['type'] == 'extra') {
            $line_left = "> {$jumlah}x {$nama}";
        } else {
            $line_left = "{$jumlah}x {$nama}";
        }

        $line_right = number_format($harga, 0, ',', '.');
        $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
    }

    // === FOOTER ===
    $out .= str_repeat("-", $width) . "\n";
    $out .= "Alasan: " . $alasan_void . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= date('d/m/Y H:i:s') . "\n";

    return $out;
}


public function cetak_void($transaksi_id) {
    $produk_void = $this->db
        ->select('pv.*, d.nama_produk, e.nama_extra')
        ->from('pr_void pv')
        ->join('pr_produk d', 'pv.pr_produk_id = d.id', 'left')
        ->join('pr_produk_extra e', 'pv.produk_extra_id = e.id', 'left')
        ->where('pv.pr_transaksi_id', $transaksi_id)
        ->where('pv.is_printed', 0) // ✅ hanya yang belum dicetak
        ->where('pv.created_at >=', date('Y-m-d') . ' 00:00:00')
        ->get()
        ->result();

    if (!$produk_void) {
        return ['status' => 'error', 'message' => 'Tidak ada produk void untuk dicetak.'];
    }

    // Load data printer
    $this->load->model('Printer_model');
    $printers = $this->Printer_model->get_all();
    $this->load->model('Setting_model');
    $struk_data = $this->Setting_model->get_data_struk();

    $hasil = [];

    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);
        if (!in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) {
            continue;
        }

        $produk_divisi = array_filter($produk_void, function ($item) use ($lokasi) {
            if ($lokasi == 'CHECKER') return true;
            if (isset($item->divisi) && strtoupper($item->divisi) == $lokasi) return true;
            return false;
        });

        if (empty($produk_divisi)) {
            $hasil[] = "ℹ️ Tidak ada produk void untuk $lokasi.";
            continue;
        }

        // Buat struk void
        $struk = $this->generate_void_struk($produk_divisi, $printer, $struk_data, $lokasi);

        if (trim($struk) !== '') {
            $res = $this->send_to_python_service($lokasi, $struk);
            if ($res === true) {
                $hasil[] = "✅ Void dikirim ke $lokasi.";
            } else {
                $hasil[] = "❌ Gagal kirim void ke $lokasi.";
            }
        }
    }

        // ✅ Setelah cetak, update semua void item jadi printed
        $void_ids = array_column($produk_void, 'id');
        $this->db->where_in('id', $void_ids)->update('pr_void', ['is_printed' => 1]);

        // 🔥 Sinkronisasi ke VPS
        $this->load->model('Api_model');
        $void_data = $this->db->where_in('id', $void_ids)->get('pr_void')->result_array();
        if (!empty($void_data)) {
            $this->Api_model->kirim_data('pr_void', $void_data);
        }

    return ['status' => 'success', 'message' => implode("\n", $hasil)];
}


public function simpan_pembayaran($transaksi_id, $pembayaran, $kasir_id)
{
    $this->db->trans_start();

    foreach ($pembayaran as $p) {
        $metode_id   = intval($p['metode_id']);
        $jumlah      = intval($p['jumlah']) ?: 0;
        $keterangan  = isset($p['keterangan']) ? $p['keterangan'] : '';

        // Skip jika jumlah = 0
        if ($jumlah <= 0) continue;

        // Cek apakah sudah ada metode yang sama untuk transaksi ini
        $existing = $this->db
            ->where('transaksi_id', $transaksi_id)
            ->where('metode_id', $metode_id)
            ->get('pr_pembayaran')
            ->row();

        if ($existing) {
            // TIMPA nilai jumlah DENGAN yang baru
            $this->db->where('id', $existing->id)->update('pr_pembayaran', [
                'jumlah' => $jumlah,
                'keterangan' => $keterangan ?: $existing->keterangan,
                'kasir_id' => $kasir_id,
                'waktu_bayar' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->db->insert('pr_pembayaran', [
                'transaksi_id' => $transaksi_id,
                'metode_id' => $metode_id,
                'jumlah' => $jumlah,
                'keterangan' => $keterangan,
                'kasir_id' => $kasir_id,
                'waktu_bayar' => date('Y-m-d H:i:s')
            ]);
        }
    }

    $this->db->trans_complete();
}


public function generate_refund_struk($transaksi, $produk_refund, $printer, $struk_data, $lokasi)
{
    $out = '';
    $width = 32; // lebar struk (optional ambil dari printer setting)
    $waktu_refund = date('d-m-Y H:i'); // waktu saat ini

    if (empty($produk_refund)) {
        return '';
    }

    $no_transaksi = $transaksi['no_transaksi'] ?? '-';
    $kode_refund = $transaksi['kode_refund'] ?? '-';
    $customer = $transaksi['customer'] ?? '-';
    $nomor_meja = $transaksi['nomor_meja'] ?? '-';
    $kasir_order = $transaksi['kasir_order'] ?? '-';
    $alasan_refund = $produk_refund[0]['alasan'] ?? '-';

    $lokasi = strtoupper($printer['lokasi_printer']);
    $isChecker = ($lokasi == 'CHECKER');

    $out .= $this->center_text("[ $lokasi REFUND ]", $width) . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= "No: " . $no_transaksi . "\n";
    $out .= "Kode: " . $kode_refund . "\n";
    $out .= "Order: " . $kasir_order . "\n";
    $out .= "Customer: " . $customer . "\n";
    $out .= "Meja: " . $nomor_meja . "\n";
    $out .= "Refund: " . $waktu_refund . "\n";
    $out .= str_repeat("-", $width) . "\n";

    $total = 0;

    foreach ($produk_refund as $item) {
        $jumlah = intval($item['jumlah']);
        $harga = intval($item['harga']);
        $subtotal = $jumlah * $harga;
        $total += $subtotal;

        if (!empty($item['nama_extra'])) {
            $line_left = "> {$jumlah}x {$item['nama_extra']}";
        } else {
            $line_left = "{$jumlah}x {$item['nama_produk']}";
        }

        $line_right = number_format($subtotal, 0, ',', '.');
        $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
    }

    $out .= str_repeat("-", $width) . "\n";
    $out .= $this->format_struk_line("Total Refund", number_format($total, 0, ',', '.'), $width) . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= "Alasan: " . $alasan_refund . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= date('d/m/Y H:i:s') . "\n";

    return $out;
}



public function get_daftar_refund($tanggal_awal, $tanggal_akhir)
{
    return $this->db
        ->select('r.kode_refund, r.no_transaksi, t.customer, t.nomor_meja, MAX(r.waktu_refund) as waktu, COUNT(*) as jumlah_item')
        ->from('pr_refund r')
        ->join('pr_transaksi t', 't.id = r.pr_transaksi_id', 'left')
        ->where('DATE(r.created_at) >=', $tanggal_awal)
        ->where('DATE(r.created_at) <=', $tanggal_akhir)
        ->group_by('r.kode_refund')
        ->order_by('r.created_at', 'DESC')
        ->get()
        ->result();
}

public function get_by_kode($kode_refund)
{
    return $this->db
        ->select('r.*, p.nama_produk, e.nama_extra, t.customer, t.nomor_meja, m.metode_pembayaran')
        ->from('pr_refund r')
        ->join('pr_detail_transaksi d', 'r.detail_transaksi_id = d.id', 'left')
        ->join('pr_produk p', 'd.pr_produk_id = p.id', 'left')
        ->join('pr_detail_extra e', 'r.detail_extra_id = e.id', 'left')
        ->join('pr_transaksi t', 'r.pr_transaksi_id = t.id', 'left')
        ->join('pr_metode_pembayaran m', 'r.metode_pembayaran_id = m.id', 'left')
        ->where('r.kode_refund', $kode_refund)
        ->get()
        ->result();
}


// UNTUK PENDING ORDER
public function get_pending_orders_filtered($tanggal_awal, $tanggal_akhir) {
    return $this->db->select('id, no_transaksi, customer, nomor_meja, sisa_pembayaran, DATE(waktu_order) as tanggal')
                    ->from('pr_transaksi')
                    ->where('waktu_bayar IS NULL')
                    ->where('DATE(waktu_order) >=', $tanggal_awal)
                    ->where('DATE(waktu_order) <=', $tanggal_akhir)
                    ->order_by('waktu_order', 'DESC')
                    ->get()
                    ->result_array();
}

public function get_jenis_order()
{
    return $this->db->order_by('id', 'ASC')->get('pr_jenis_order')->result_array();
}

public function get_kategori_produk()
{
    return $this->db->order_by('nama_kategori', 'ASC')->get('pr_kategori')->result_array();
}
public function get_list_printer()
{
    return $this->db->order_by('lokasi_printer', 'ASC')->get('pr_printer')->result_array();
}

/// PAKET
public function insert_detail_paket($transaksi_id, $detail_id, $paket_id)
{
    $this->db->insert('pr_detail_transaksi_paket', [
        'pr_transaksi_id' => $transaksi_id,
        'pr_detail_transaksi_id' => $detail_id,
        'pr_produk_paket_id' => $paket_id,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
}



}