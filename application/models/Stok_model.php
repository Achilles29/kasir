<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

  public function get_all()
  {
    return $this->db->select('s.*, b.nama_barang, d.nama_divisi')
                    ->from('pr_stok_bahan_baku s')
                    ->join('bl_db_belanja b', 'b.id = s.bahan_id')
                    ->join('bl_divisi d', 'd.id = s.divisi_id')
                    // Hapus baris ini atau ganti dengan kolom yang ada:
                    ->order_by('s.id', 'DESC') // contoh alternatif
                    ->get()->result_array();
  }

  public function get_bahan()
  {
    return $this->db->get('bl_db_belanja')->result_array();
  }

  public function get_divisi()
  {
    return $this->db->get('bl_divisi')->result_array();
  }

  public function insert_manual($data)
  {
    $insert = [
      'bahan_id' => $data['bahan_id'],
      'divisi_id' => $data['divisi_id'],
      'tanggal' => $data['tanggal'],
      'stok_penyesuaian' => $data['stok_penyesuaian'],
      'stok_sisa' => $data['stok_penyesuaian'],
      'keterangan' => $data['keterangan'],
    ];
    $this->db->insert('pr_stok_bahan_baku', $insert);
  }

  // Fungsi update otomatis dari purchase, store request, dan penjualan bisa dibuat terpisah (lihat bawah)
// File: application/models/Stok_model.php
// public function update_from_purchase($bahan_id, $divisi_id, $tanggal, $jumlah)
// {
//   $this->db->where('bahan_id', $bahan_id);
//   $this->db->where('divisi_id', $divisi_id);
//   $this->db->where('tanggal', $tanggal);
//   $existing = $this->db->get('pr_stok_bahan_baku')->row_array();

//   if ($existing) {
//     $this->db->set('stok_masuk', 'stok_masuk + '.$jumlah, FALSE);
//     $this->db->set('stok_sisa', 'stok_sisa + '.$jumlah, FALSE);
//     $this->db->where('id', $existing['id']);
//     $this->db->update('pr_stok_bahan_baku');
//   } else {
//     $this->db->insert('pr_stok_bahan_baku', [
//       'bahan_id' => $bahan_id,
//       'divisi_id' => $divisi_id,
//       'tanggal' => $tanggal,
//       'stok_awal' => 0,
//       'stok_masuk' => $jumlah,
//       'stok_keluar' => 0,
//       'stok_sisa' => $jumlah,
//       'keterangan' => 'Otomatis dari purchase'
//     ]);
//   }
// }

  // Tambah atau update stok masuk dari purchase
public function update_stok_masuk($bahan_id, $divisi_id, $tanggal_input, $jumlah, $hpp = 0, $sumber = 'purchase') {
    // Hanya insert jika divisi valid (1, 2, 3)
    if (!in_array((int)$divisi_id, [1, 2, 3])) return;

    // Cek apakah bahan adalah tipe produksi
    $tipe = $this->db->get_where('bl_db_belanja', ['id' => $bahan_id])->row();
    if (!$tipe || $tipe->id_tipe_produksi != 1) return;

    // Insert atau update stok
    $stok = $this->db->get_where('pr_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id
    ])->row_array();

    if ($stok) {
        $this->db->where('id', $stok['id'])->update('pr_stok_bahan_baku', [
            'stok_masuk' => $stok['stok_masuk'] + $jumlah,
            'stok_sisa' => $stok['stok_sisa'] + $jumlah
        ]);
    } else {
        $this->db->insert('pr_stok_bahan_baku', [
            'bahan_id' => $bahan_id,
            'divisi_id' => $divisi_id,
            'stok_awal' => 0,
            'stok_masuk' => $jumlah,
            'stok_keluar' => 0,
            'stok_penyesuaian' => 0,
            'stok_sisa' => $jumlah,
            'hpp' => $hpp
        ]);
    }

    // Log insert
    $this->db->insert('pr_log_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id,
        'tanggal' => $tanggal_input,
        'jenis_transaksi' => 'purchase',
        'jumlah' => $jumlah,
        'hpp' => $hpp,
        'keterangan' => 'Otomatis dari ' . $sumber,
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

  // Update stok keluar dari Store Request
public function update_stok_masuk_produksi($bahan_id, $divisi_id, $tanggal_input, $jumlah, $hpp = 0, $keterangan = 'Dari Store Request')
{
    if (!in_array((int)$divisi_id, [1, 2, 3])) return;

    $tipe = $this->db->get_where('bl_db_belanja', ['id' => $bahan_id])->row();
    if (!$tipe || $tipe->id_tipe_produksi != 1) return;

    $stok = $this->db->get_where('pr_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id
    ])->row_array();

    if ($stok) {
        $this->db->where('id', $stok['id'])->update('pr_stok_bahan_baku', [
            'stok_masuk' => $stok['stok_masuk'] + $jumlah,
            'stok_sisa' => $stok['stok_sisa'] + $jumlah,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        $this->db->insert('pr_stok_bahan_baku', [
            'bahan_id' => $bahan_id,
            'divisi_id' => $divisi_id,
            'stok_awal' => 0,
            'stok_masuk' => $jumlah,
            'stok_keluar' => 0,
            'stok_penyesuaian' => 0,
            'stok_sisa' => $jumlah,
            'hpp' => $hpp,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    $this->db->insert('pr_log_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id,
        'tanggal' => $tanggal_input,
        'jenis_transaksi' => 'store_request',
        'jumlah' => $jumlah,
        'hpp' => $hpp,
        'keterangan' => $keterangan,
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

  // Fungsi pengurangan stok_masuk jika purchase dihapus
public function kurangi_stok_masuk($bahan_id, $divisi_id, $jumlah, $tanggal_log = null, $keterangan = '')
{
    if (!in_array((int)$divisi_id, [1, 2, 3])) return;

    $tipe = $this->db->get_where('bl_db_belanja', ['id' => $bahan_id])->row();
    if (!$tipe || $tipe->id_tipe_produksi != 1) return;

    // Update stok
    $this->db->set('stok_masuk', 'stok_masuk - ' . $jumlah, false);
    $this->db->set('stok_sisa', 'stok_sisa - ' . $jumlah, false);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->where('bahan_id', $bahan_id);
    $this->db->where('divisi_id', $divisi_id);
    $this->db->update('pr_stok_bahan_baku');

    // Simpan ke log
    $this->db->insert('pr_log_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id,
        'tanggal' => $tanggal_log ?? date('Y-m-d'),
        'jenis_transaksi' => 'purchase',
        'jumlah' => -$jumlah,
        'hpp' => 0,
        'keterangan' => $keterangan ?: 'Pengurangan dari hapus purchase',
        'created_at' => date('Y-m-d H:i:s')
    ]);
}


  // Fungsi pengurangan stok_keluar jika SR dihapus
public function kurangi_stok_masuk_produksi($bahan_id, $divisi_id, $jumlah, $tanggal_log = null, $keterangan = '')
{
    if (!in_array((int)$divisi_id, [1, 2, 3])) return;

    $tipe = $this->db->get_where('bl_db_belanja', ['id' => $bahan_id])->row();
    if (!$tipe || $tipe->id_tipe_produksi != 1) return;

    $this->db->set('stok_masuk', 'stok_masuk - ' . $jumlah, false);
    $this->db->set('stok_sisa', 'stok_sisa - ' . $jumlah, false);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->where('bahan_id', $bahan_id);
    $this->db->where('divisi_id', $divisi_id);
    $this->db->update('pr_stok_bahan_baku');

    $this->db->insert('pr_log_stok_bahan_baku', [
        'bahan_id' => $bahan_id,
        'divisi_id' => $divisi_id,
        'tanggal' => $tanggal_log ?? date('Y-m-d'),
        'jenis_transaksi' => 'store_request',
        'jumlah' => -$jumlah,
        'hpp' => 0,
        'keterangan' => $keterangan ?: 'Hapus Store Request',
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

private function get_divisi_from_jenis_pengeluaran($jenis_pengeluaran) {
    $map = [
        2 => 1, // jenis 2 = BAR → divisi_id = 1
        3 => 2, // jenis 3 = KITCHEN → divisi_id = 2
        5 => 3  // jenis 5 = EVENT → divisi_id = 3
    ];
    return $map[$jenis_pengeluaran] ?? 0;
}

public function get_log()
{
    return $this->db->select('l.*, b.nama_barang, d.nama_divisi')
        ->from('pr_log_stok_bahan_baku l')
        ->join('bl_db_belanja b', 'b.id = l.bahan_id')
        ->join('bl_divisi d', 'd.id = l.divisi_id')
        ->order_by('l.tanggal', 'DESC')
        ->get()->result_array();
}

}
