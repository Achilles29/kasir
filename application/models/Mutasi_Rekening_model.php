<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_Rekening_model extends CI_Model {

    // Fetch mutasi rekening data based on month filter
    public function get_mutasi_rekening_data($bulan) {
        $this->db->select('
            bl_mutasi_kas_rekening.id,
            bl_mutasi_kas_rekening.tanggal,
            bl_mutasi_kas_rekening.bl_rekening_id_sumber,
            bl_mutasi_kas_rekening.bl_rekening_id_tujuan,
            bl_mutasi_kas_rekening.jumlah,
            bl_mutasi_kas_rekening.keterangan,
            sumber.nama_rekening AS sumber_rekening,
            tujuan.nama_rekening AS tujuan_rekening
        ');
        $this->db->from('bl_mutasi_kas_rekening');
        $this->db->join('bl_rekening AS sumber', 'bl_mutasi_kas_rekening.bl_rekening_id_sumber = sumber.id', 'left');
        $this->db->join('bl_rekening AS tujuan', 'bl_mutasi_kas_rekening.bl_rekening_id_tujuan = tujuan.id', 'left');
        $this->db->where('bl_mutasi_kas_rekening.tanggal >=', $bulan . '-01');
        $this->db->where('bl_mutasi_kas_rekening.tanggal <=', $bulan . '-31');
        $query = $this->db->get();
        return $query->result_array();
    }


    // Add a new record to the table
    public function add_mutasi_rekening($data) {
        $this->db->insert('bl_mutasi_kas_rekening', $data);
    }

    // Update a record
    public function update_mutasi_rekening($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('bl_mutasi_kas_rekening', $data);
    }

    // Delete a record
    public function delete_mutasi_rekening($id) {
        $this->db->where('id', $id);
        $this->db->delete('bl_mutasi_kas_rekening');
    }
}
