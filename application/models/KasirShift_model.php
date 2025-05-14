<?php
class KasirShift_model extends CI_Model {

    public function get_open_shift($kasir_id)
    {
        return $this->db->where('kasir_id', $kasir_id)
                        ->where('status', 'OPEN')
                        ->order_by('waktu_mulai', 'DESC')
                        ->get('pr_kasir_shift')
                        ->row();
    }

public function start_shift($kasir_id, $modal_awal, $keterangan = null)
{
    $data = [
        'kasir_id' => $kasir_id,
        'modal_awal' => $modal_awal,
        'keterangan' => $keterangan,
        'waktu_mulai' => date('Y-m-d H:i:s'),
        'status' => 'OPEN'
    ];
    $this->db->insert('pr_kasir_shift', $data);
    return $this->db->insert_id();
}


    // Kasir_model.php
    public function get_shift_aktif($kasir_id) {
        $this->db->where('kasir_id', $kasir_id);
        $this->db->where('status', 'OPEN');
        return $this->db->get('pr_kasir_shift')->row_array();
    }

public function get_shift_ke_berapa($kasir_id)
{
    $today = date('Y-m-d');

    // Ambil semua shift hari ini untuk kasir ini
    $this->db->select('keterangan');
    $this->db->where('kasir_id', $kasir_id);
    $this->db->where('DATE(waktu_mulai)', $today);
    $shifts = $this->db->get('pr_kasir_shift')->result();

    // Jika belum ada shift sama sekali hari ini
    if (empty($shifts)) {
        return "SHIFT 1";
    }

    // Cari angka terbesar dari shift yang sudah digunakan
    $used_shift = [];
    foreach ($shifts as $s) {
        if (preg_match('/SHIFT (\d+)/', $s->keterangan, $match)) {
            $used_shift[] = (int)$match[1];
        }
    }

    $next_shift = 1;
    while (in_array($next_shift, $used_shift)) {
        $next_shift++;
    }

    return "SHIFT " . $next_shift;
}

public function get_last_shift($kasir_id)
{
    return $this->db
        ->where('kasir_id', $kasir_id)
        ->where('status', 'CLOSE')
        ->order_by('waktu_tutup', 'DESC')
        ->limit(1)
        ->get('pr_kasir_shift')
        ->row_array();
}

public function get_metode_pembayaran_shift($shift_id)
{
    return $this->db
        ->select('m.metode_pembayaran, SUM(p.jumlah) as total')
        ->from('pr_pembayaran p')
        ->join('pr_metode_pembayaran m', 'm.id = p.metode_id')
        ->where('p.shift_id', $shift_id)
        ->group_by('p.metode_id')
        ->get()->result_array();
}

public function get_refund_per_metode_shift($kasir_id, $start, $end)
{
    return $this->db->select('m.metode_pembayaran, SUM(rf.harga * rf.jumlah) as total')
        ->from('pr_refund rf')
        ->join('pr_metode_pembayaran m', 'm.id = rf.metode_pembayaran_id')
        ->where('rf.refund_by', $kasir_id)
        ->where('rf.waktu_refund >=', $start)
        ->where('rf.waktu_refund <=', $end)
        ->group_by('rf.metode_pembayaran_id')
        ->get()->result_array();
}

public function get_rekening_penerimaan_shift($kasir_id, $start, $end)
{
    return $this->db->select('rk.nama_rekening, SUM(p.jumlah) as total')
        ->from('pr_pembayaran p')
        ->join('pr_metode_pembayaran m', 'm.id = p.metode_id')
        ->join('bl_rekening rk', 'rk.id = m.bl_rekening_id')
        ->where('p.kasir_id', $kasir_id)
        ->where('p.waktu_bayar >=', $start)
        ->where('p.waktu_bayar <=', $end)
        ->group_by('rk.id')
        ->get()->result_array();
}

}


?>