<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_model extends CI_Model {

    // public function simpan_transaksi($data_transaksi, $items) {
    //     $this->db->trans_begin();

    //     $this->db->insert('pr_transaksi', $data_transaksi);
    //     $transaksi_id = $this->db->insert_id();

    //     $detail_id = $this->db->insert_id();
    //     if (!empty($item['extra'])) {
    //         $this->simpan_detail_extra($detail_id, $item['extra']);
    //     }

    //     if (!$transaksi_id) {
    //         $this->db->trans_rollback();
    //         return false;
    //     }

    //     foreach ($items as $item) {
    //         $this->db->insert('pr_detail_transaksi', [
    //             'pr_transaksi_id' => $transaksi_id,
    //             'pr_produk_id' => $item['pr_produk_id'],
    //             'jumlah' => $item['jumlah'],
    //             'harga' => $item['harga'],
    //             'subtotal' => $item['harga'] * $item['jumlah'],
    //             'catatan' => $item['catatan'] ?? null,
    //             'status' => null,
    //             'created_at' => date('Y-m-d H:i:s')
    //         ]);


    //         $detail_id = $this->db->insert_id();

    //         // ✅ Tambahkan pemanggilan penyimpanan extra
    //         if (!empty($item['extra'])) {
    //             $this->simpan_detail_extra($detail_id, $item['extra']);
    //         }

    //     }

    //     $this->db->trans_commit();
    //     return $transaksi_id;
    // }
    public function simpan_transaksi($data_transaksi, $items) {
    $this->db->trans_begin();

    $this->db->insert('pr_transaksi', $data_transaksi);
    $transaksi_id = $this->db->insert_id();

    if (!$transaksi_id) {
        $this->db->trans_rollback();
        return false;
    }

    foreach ($items as $item) {
        $detail_unit_id = uniqid(); // Untuk grup 1 produk

        for ($i = 0; $i < $item['jumlah']; $i++) {
            $this->db->insert('pr_detail_transaksi', [
                'pr_transaksi_id' => $transaksi_id,
                'pr_produk_id' => $item['pr_produk_id'],
                'jumlah' => 1,
                'harga' => $item['harga'],
                'catatan' => $item['catatan'] ?? null,
                'status' => null,
                'detail_unit_id' => $detail_unit_id,
                'is_printed' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $detail_id = $this->db->insert_id();

            if (!empty($item['extra'])) {
                $this->simpan_detail_extra($detail_id, $item['extra']);
            }
        }
    }

    $this->db->trans_commit();
    return $transaksi_id;
}

    public function simpan_detail_extra($detail_id, $extras) {
        foreach ($extras as $extra) {
            $this->db->insert('pr_detail_extra', [
                'detail_transaksi_id' => $detail_id,
                'pr_produk_extra_id' => $extra['id'],
                'jumlah' => $extra['jumlah'],
                'harga' => $extra['harga'],
                'subtotal' => $extra['harga'] * $extra['jumlah'],
                'sku' => $extra['sku'],
                'satuan' => $extra['satuan'],
                'hpp' => $extra['hpp'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
public function get_detail_transaksi($transaksi_id) {
    $this->db->select('d.*, t.no_transaksi, p.nama_produk');
    $this->db->from('pr_detail_transaksi d');
    $this->db->join('pr_transaksi t', 'd.pr_transaksi_id = t.id');
    $this->db->join('pr_produk p', 'd.pr_produk_id = p.id');
    $this->db->where('d.pr_transaksi_id', $transaksi_id);
    $this->db->where('d.status IS NULL');
    return $this->db->get()->result_array();
}



public function update_detail_transaksi($transaksi_id, $items, $transaksi) {
    $this->db->where('pr_transaksi_id', $transaksi_id)->where('status IS NULL');
    $existing = $this->db->get('pr_detail_transaksi')->result_array();

    // Group existing per detail_unit_id
    $existing_by_unit = [];
    foreach ($existing as $row) {
        $existing_by_unit[$row['detail_unit_id']][] = $row;
    }

    $existing_used = [];

    foreach ($items as $item) {
        if (!empty($item['detail_id']) && isset($existing_by_unit[$item['detail_id']])) {
            $unit_rows = $existing_by_unit[$item['detail_id']];
            $existing_count = count($unit_rows);
            $requested = intval($item['jumlah']);

            // ❗ Jika sudah diprint, abaikan perubahan
            if (!empty($unit_rows[0]['is_printed']) && $unit_rows[0]['is_printed'] == 1) {
                $existing_used[] = $item['detail_id'];
                continue;
            }

            // ✅ Update jumlah jika sama
            if ($requested == $existing_count) {
                $existing_used[] = $item['detail_id'];

                // Update extra & catatan
                foreach ($unit_rows as $row) {
                    $this->db->where('id', $row['id'])->update('pr_detail_transaksi', [
                        'catatan' => $item['catatan'] ?? null,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $this->db->delete('pr_detail_extra', ['detail_transaksi_id' => $row['id']]);

                    if (!empty($item['extra'])) {
                        $this->simpan_detail_extra($row['id'], $item['extra']);
                    }
                }

            } elseif ($requested < $existing_count) {
                $existing_used[] = $item['detail_id'];

                // Batalkan sisa
                $to_cancel = array_slice($unit_rows, $requested);
                foreach ($to_cancel as $cancel_row) {
                    $this->db->where('id', $cancel_row['id'])->update('pr_detail_transaksi', [
                        'status' => 'BATAL',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // Update sisa yang tetap
                $remain = array_slice($unit_rows, 0, $requested);
                foreach ($remain as $row) {
                    $this->db->where('id', $row['id'])->update('pr_detail_transaksi', [
                        'catatan' => $item['catatan'] ?? null,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $this->db->delete('pr_detail_extra', ['detail_transaksi_id' => $row['id']]);

                    if (!empty($item['extra'])) {
                        $this->simpan_detail_extra($row['id'], $item['extra']);
                    }
                }

            } elseif ($requested > $existing_count) {
                $existing_used[] = $item['detail_id'];

                // Tambah sisanya
                $detail_unit_id = $item['detail_id'];
                for ($i = 0; $i < ($requested - $existing_count); $i++) {
                    $this->db->insert('pr_detail_transaksi', [
                        'pr_transaksi_id' => $transaksi_id,
                        'pr_produk_id' => $item['pr_produk_id'],
                        'jumlah' => 1,
                        'harga' => $item['harga'],
                        'catatan' => $item['catatan'] ?? null,
                        'status' => null,
                        'detail_unit_id' => $detail_unit_id,
                        'is_printed' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $new_id = $this->db->insert_id();
                    if (!empty($item['extra'])) {
                        $this->simpan_detail_extra($new_id, $item['extra']);
                    }
                }
            }
        } else {
            // Produk baru
            if (!empty($item['detail_id'])) continue; // ❗ Jangan insert ulang produk yang sudah ada

            $detail_unit_id = uniqid();
            for ($i = 0; $i < $item['jumlah']; $i++) {
                $this->db->insert('pr_detail_transaksi', [
                    'pr_transaksi_id' => $transaksi_id,
                    'pr_produk_id' => $item['pr_produk_id'],
                    'jumlah' => 1,
                    'harga' => $item['harga'],
                    'catatan' => $item['catatan'] ?? null,
                    'status' => null,
                    'detail_unit_id' => $detail_unit_id,
                    'is_printed' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $new_id = $this->db->insert_id();
                if (!empty($item['extra'])) {
                    $this->simpan_detail_extra($new_id, $item['extra']);
                }
            }
        }
    }
    
    // Hapus baris dengan detail_unit_id yang tidak digunakan, dan belum dicetak
    foreach ($existing_by_unit as $unit_id => $rows) {
        if (!in_array($unit_id, $existing_used)) {
            foreach ($rows as $row) {
                if ($row['is_printed'] == 0) {
                    // Hapus extra terlebih dahulu
                    $this->db->where('detail_transaksi_id', $row['id'])->delete('pr_detail_extra');
                    // Hapus detail transaksi
                    $this->db->where('id', $row['id'])->delete('pr_detail_transaksi');
                }
            }
        }
    }

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
        // ✅ Ganti query ini agar subtotal dihitung langsung
        $transaksi['items'] = $this->db
            ->select('d.*, p.nama_produk, (d.jumlah * d.harga) AS subtotal')
            ->from('pr_detail_transaksi d')
            ->join('pr_produk p', 'd.pr_produk_id = p.id')
            ->where('d.pr_transaksi_id', $id)
            ->where('d.status IS NULL') // jika hanya ingin item aktif
            ->get()->result_array();
    }

    return $transaksi;
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

    // if (!empty($tampilan['show_kasir_order'])) 
    //     $out .= "Kasir Order: " . $transaksi['kasir_order'] . "\n";

    // if (!empty($tampilan['show_kasir_bayar']) && $transaksi['kasir_bayar']) 
    //     $out .= "Kasir Bayar: " . $transaksi['kasir_bayar'] . "\n";
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

    $divisi_id = $printer['divisi'];
    $isKasir = strtoupper($printer['lokasi_printer']) === 'KASIR';

    foreach ($transaksi['items'] as $item) {
        $produk = $this->db->select('k.pr_divisi_id')->from('pr_produk p')
            ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
            ->where('p.id', $item['pr_produk_id'])->get()->row_array();

        if ($divisi_id && $produk['pr_divisi_id'] != $divisi_id) continue;

        $line_left = "{$item['jumlah']}x {$item['nama_produk']}";
        $line_right = $isKasir ? number_format($item['harga'] * $item['jumlah'], 0, ',', '.') : '';
        $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";

        // Extra
        $extras = $this->db->get_where('pr_detail_extra', ['detail_transaksi_id' => $item['id']])->result_array();
        foreach ($extras as $ex) {
            $extra_info = $this->db->get_where('pr_produk_extra', ['id' => $ex['pr_produk_extra_id']])->row_array();
            // $nama = $ex['nama_extra'] ?? $ex['satuan'];
            $nama = $extra_info['nama_extra'] ?? 'Extra';
            $line_left = "  > {$ex['jumlah']}x {$nama}";
            $line_right = $isKasir ? number_format($ex['harga'] * $ex['jumlah'], 0, ',', '.') : '';
            $out .= $this->format_struk_line($line_left, $line_right, $width) . "\n";
        }

        // Note
        if (!empty($item['catatan'])) {
            $note_lines = explode("\n", wordwrap("- " . $item['catatan'], $width - 2));
            foreach ($note_lines as $line) {
                $out .= "  $line\n";
            }
        }
    }

    $out .= str_repeat("-", $width) . "\n";

    if (!empty($tampilan['show_custom_footer'])) {
        $lines = explode("\n", wordwrap($struk_data['custom_footer'], $width));
        foreach ($lines as $line) {
            $out .= $this->center_text(strtoupper($line), $width) . "\n";
        }
    }

    return $out;
}

private function format_struk_line($left, $right, $width = 32) {
    $left = trim($left);
    $right = trim($right);

    // Jika terlalu panjang, potong left
    if (strlen($left) + strlen($right) > $width) {
        $maxLeft = $width - strlen($right) - 1;
        $left = substr($left, 0, $maxLeft);
    }

    $space = $width - strlen($left) - strlen($right);
    return $left . str_repeat(' ', max(1, $space)) . $right;
}

private function center_text($text, $width = 32) {
    $text = trim($text);
    $padding = floor(($width - strlen($text)) / 2);
    return str_repeat(' ', max(0, $padding)) . $text;
}


public function simpan_pembayaran($transaksi_id, $pembayaran, $kasir_id, $total_bayar) {
    $this->db->trans_start();

    foreach ($pembayaran as $p) {
        $this->db->insert('pr_pembayaran', [
            'transaksi_id' => $transaksi_id,
            'metode_id' => $p['metode_id'],
            'jumlah' => $p['jumlah'],
            'waktu_bayar' => date('Y-m-d H:i:s'),
            'keterangan' => $p['keterangan'],
            'kasir_id' => $kasir_id
        ]);
    }

    $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
        'kasir_bayar' => $kasir_id,
        'waktu_bayar' => date('Y-m-d H:i:s'),
        'total_pembayaran' => $total_bayar,
        'sisa_pembayaran' => 0,
        'status_pembayaran' => 'LUNAS',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->db->where('pr_transaksi_id', $transaksi_id)
             ->update('pr_detail_transaksi', ['status' => 'BERHASIL']);

    $this->db->trans_complete();
}


}