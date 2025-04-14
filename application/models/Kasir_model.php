<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_model extends CI_Model {

    public function simpan_transaksi($data_transaksi, $items) {
        $this->db->trans_begin();

        $this->db->insert('pr_transaksi', $data_transaksi);
        $transaksi_id = $this->db->insert_id();

        $detail_id = $this->db->insert_id();
        if (!empty($item['extra'])) {
            $this->simpan_detail_extra($detail_id, $item['extra']);
        }

        if (!$transaksi_id) {
            $this->db->trans_rollback();
            return false;
        }

        foreach ($items as $item) {
            $this->db->insert('pr_detail_transaksi', [
                'pr_transaksi_id' => $transaksi_id,
                'pr_produk_id' => $item['pr_produk_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $item['harga'] * $item['jumlah'],
                'catatan' => $item['catatan'] ?? null,
                'status' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);


            $detail_id = $this->db->insert_id();

            // ✅ Tambahkan pemanggilan penyimpanan extra
            if (!empty($item['extra'])) {
                $this->simpan_detail_extra($detail_id, $item['extra']);
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


public function update_detail_transaksi($transaksi_id, $items_baru, $transaksi)
{
    $kasir_id = $this->session->userdata('pegawai_id');

    $items_lama = $this->db->where('pr_transaksi_id', $transaksi_id)
        ->get('pr_detail_transaksi')->result_array();

    $map_lama = [];
    foreach ($items_lama as $item) {
        $map_lama[$item['pr_produk_id']] = $item;
    }

    foreach ($items_baru as $item) {
        $produk_id = $item['pr_produk_id'];
        $jumlah_baru = intval($item['jumlah']);
        $harga = intval($item['harga']);
        $catatan = $item['catatan'] ?? null;
        $subtotal = $jumlah_baru * $harga;

        if (isset($map_lama[$produk_id])) {
            $item_lama = $map_lama[$produk_id];
            $jumlah_lama = $item_lama['jumlah'];

            if ($jumlah_baru < $jumlah_lama) {
                // VOID
                $this->db->insert('pr_void', [
                    'pr_transaksi_id' => $transaksi_id,
                    'no_transaksi' => $transaksi['no_transaksi'],
                    'detail_transaksi_id' => $item_lama['id'],
                    'pr_produk_id' => $produk_id,
                    'nama_produk' => $item_lama['nama_produk'],
                    'jumlah' => $jumlah_lama - $jumlah_baru,
                    'harga' => $harga,
                    'subtotal' => ($jumlah_lama - $jumlah_baru) * $harga,
                    'catatan' => $item_lama['catatan'],
                    'alasan' => 'Jumlah dikurangi saat ubah pesanan',
                    'void_by' => $kasir_id,
                    'waktu' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } elseif ($jumlah_baru > $jumlah_lama) {
                // ⛔ Tidak boleh menambah jumlah
                throw new Exception("Tidak boleh menambah kuantitas langsung.");
            }

            $this->db->where('id', $item_lama['id'])->update('pr_detail_transaksi', [
                'jumlah' => $jumlah_baru,
                'subtotal' => $subtotal,
                'catatan' => $catatan,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->where('detail_transaksi_id', $item_lama['id'])->delete('pr_detail_extra');
            if (!empty($item['extra'])) {
                $this->simpan_detail_extra($item_lama['id'], $item['extra']);
            }

        } else {
            // Produk baru
            $this->db->insert('pr_detail_transaksi', [
                'pr_transaksi_id' => $transaksi_id,
                'pr_produk_id' => $produk_id,
                'jumlah' => $jumlah_baru,
                'harga' => $harga,
                'subtotal' => $subtotal,
                'catatan' => $catatan,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $detail_id = $this->db->insert_id();

            if (!empty($item['extra'])) {
                $this->simpan_detail_extra($detail_id, $item['extra']);
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
        $this->db->select('d.*, p.nama_produk');
        $this->db->from('pr_detail_transaksi d');
        $this->db->join('pr_produk p', 'd.pr_produk_id = p.id');
        $this->db->where('d.pr_transaksi_id', $id);
        $transaksi['items'] = $this->db->get()->result_array();
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



}