<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PenjualanKasir_model extends CI_Model {

public function get_penjualan($tanggal_awal, $tanggal_akhir, $rekening_id = null, $search_nota = null, $metode_pembayaran = null, $limit = 10, $start = 0)
{
    $this->db->select('
        pm.tanggal,
        pm.no_nota,
        pm.waktu_order,
        pm.waktu_bayar,
        pm.penjualan,
        pm.penyesuaian,
        pm.selisih,
        pm.keterangan,
        pm.rekening_id,
        pm.metode_pembayaran,
        r.nama_rekening AS rekening
    ');
    $this->db->from('bl_penjualan_majoo pm');
    $this->db->join('bl_rekening r', 'pm.rekening_id = r.id', 'left');
    $this->db->where('pm.tanggal >=', $tanggal_awal);
    $this->db->where('pm.tanggal <=', $tanggal_akhir);

    if ($rekening_id) {
        $this->db->where('pm.rekening_id', $rekening_id);
    }
    if ($search_nota) {
        $this->db->like('pm.no_nota', $search_nota);
    }
    if ($metode_pembayaran) {
        $this->db->where('pm.metode_pembayaran', $metode_pembayaran);
    }

    $this->db->limit($limit, $start);

    $query = $this->db->get();
    return $query->result_array();
}

public function count_penjualan($tanggal_awal, $tanggal_akhir, $rekening_id = null, $search_nota = null, $metode_pembayaran = null)
{
    $this->db->from('bl_penjualan_majoo pm');
    $this->db->where('pm.tanggal >=', $tanggal_awal);
    $this->db->where('pm.tanggal <=', $tanggal_akhir);

    if ($rekening_id) {
        $this->db->where('pm.rekening_id', $rekening_id);
    }
    if ($search_nota) {
        $this->db->like('pm.no_nota', $search_nota);
    }
    if ($metode_pembayaran) {
        $this->db->where('pm.metode_pembayaran', $metode_pembayaran);
    }

    return $this->db->count_all_results();
}

public function get_metode_pembayaran_list()
{
    $this->db->distinct();
    $this->db->select('metode_pembayaran');
    $this->db->from('bl_penjualan_majoo');
    $this->db->where('metode_pembayaran IS NOT NULL');
    $this->db->where('metode_pembayaran !=', '');
    $query = $this->db->get();

    $result = [];
    foreach ($query->result_array() as $row) {
        $result[$row['metode_pembayaran']] = $row['metode_pembayaran'];
    }
    return $result;
}

public function update_field($tanggal, $no_nota, $field, $value)
{
    $this->db->where('tanggal', $tanggal);
    $this->db->where('no_nota', $no_nota);
    return $this->db->update('bl_penjualan_majoo', [$field => $value]);
}


public function update_null_penyesuaian_selisih($tanggal_awal, $tanggal_akhir, $rekening_id = null)
{
    // Update nilai penyesuaian jika null
    $this->db->set('penyesuaian', 'penjualan', false);
    $this->db->where('penyesuaian IS NULL', null, false);
    $this->db->where('tanggal >=', $tanggal_awal);
    $this->db->where('tanggal <=', $tanggal_akhir);
    if ($rekening_id) {
        $this->db->where('rekening_id', $rekening_id);
    }
    $this->db->update('bl_penjualan_majoo');

    // Update nilai selisih jika null
    $this->db->set('selisih', 'penyesuaian - penjualan', false);
    $this->db->where('selisih IS NULL', null, false);
    $this->db->where('tanggal >=', $tanggal_awal);
    $this->db->where('tanggal <=', $tanggal_akhir);
    if ($rekening_id) {
        $this->db->where('rekening_id', $rekening_id);
    }
    $this->db->update('bl_penjualan_majoo');
}


public function update_penyesuaian($no_nota, $penyesuaian)
{
    $this->db->set('penyesuaian', $penyesuaian);
    $this->db->set('selisih', 'penyesuaian - penjualan', false); // Sesuai kebutuhan
    $this->db->where('no_nota', $no_nota);
    return $this->db->update('bl_penjualan_majoo');
}


public function update_keterangan($no_nota, $keterangan)
{
    $this->db->set('keterangan', $keterangan);
    $this->db->where('no_nota', $no_nota);
    return $this->db->update('bl_penjualan_majoo');
}

public function kirim_mutasi($tanggal, $selisih, $keterangan, $rekening_id)
{
    $jenis_mutasi = $selisih < 0 ? 'keluar' : 'masuk';
    $jumlah = abs($selisih); // Gunakan nilai absolut

    $data = [
        'tanggal' => $tanggal,
        'bl_rekening_id' => $rekening_id,
        'jenis_mutasi' => $jenis_mutasi,
        'jumlah' => number_format($jumlah, 2, '.', ''), // Pastikan format benar
        'keterangan' => $keterangan
    ];

    return $this->db->insert('bl_mutasi_kas', $data);
}

// public function kirim_mutasi($tanggal, $selisih, $keterangan, $rekening_id)
// {
//     $jenis_mutasi = $selisih < 0 ? 'keluar' : 'masuk'; // Tentukan jenis mutasi
//     $jumlah = abs($selisih); // Ambil nilai absolut dari selisih

//     $data = [
//         'tanggal' => $tanggal,
//         'bl_rekening_id' => $rekening_id, // Masukkan rekening_id ke dalam data
//         'jenis_mutasi' => $jenis_mutasi,
//         'jumlah' => $jumlah, // Gunakan kolom jumlah
//         'keterangan' => $keterangan
//     ];

//     return $this->db->insert('bl_mutasi_kas', $data);
// }

    public function update_selisih($id)
    {
        $this->db->set('selisih', 'penyesuaian - penjualan', FALSE);
        $this->db->where('id', $id);
        $this->db->update('bl_penjualan_majoo');
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('bl_penjualan_majoo', ['id' => $id])->row_array();
    }

public function get_all_penjualan()
{
    $this->db->select('
        pm.tanggal,
        pm.no_nota,
        pm.waktu_order,
        pm.waktu_bayar,
        pm.penjualan,
        pm.penyesuaian,
        pm.selisih,
        pm.keterangan,
        pm.rekening_id,
        pm.metode_pembayaran,
        r.nama_rekening AS rekening
    ');
    $this->db->from('bl_penjualan_majoo pm');
    $this->db->join('bl_rekening r', 'pm.rekening_id = r.id', 'left');
    $this->db->order_by('pm.tanggal', 'ASC');  // Order by tanggal

    $query = $this->db->get();
    return $query->result_array();
}

}
