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

    public function start_shift($kasir_id, $modal_awal)
    {
        $data = [
            'kasir_id' => $kasir_id,
            'modal_awal' => $modal_awal,
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

}


?>