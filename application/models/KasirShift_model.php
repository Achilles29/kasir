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


}


?>