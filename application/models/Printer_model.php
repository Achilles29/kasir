<?php

class Printer_model extends CI_Model {

    public function get_all() {
        return $this->db
            ->select('pr_printer.*, pr_divisi.nama_divisi')
            ->join('pr_divisi', 'pr_divisi.id = pr_printer.divisi', 'left') // pakai LEFT JOIN
            ->get('pr_printer')->result_array();

    }
    public function get_by_divisi_nama($nama_divisi) {
        $this->db->select('pr_printer.*, pr_divisi.nama_divisi');
        $this->db->join('pr_divisi', 'pr_divisi.id = pr_printer.divisi');
        return $this->db->get_where('pr_printer', ['LOWER(pr_divisi.nama_divisi)' => strtolower($nama_divisi)])->row_array();
    }
public function get_all_with_divisi() {
    $this->db->select('pr_printer.*, pr_divisi.nama_divisi as divisi_nama');
    $this->db->join('pr_divisi', 'pr_printer.divisi = pr_divisi.id', 'left');
    return $this->db->get('pr_printer')->result_array();
}

    public function get_by_divisi($divisi_id) {
        return $this->db->get_where('pr_printer', ['divisi' => $divisi_id])->row_array();
    }
    public function get_by_id($id)
    {
        return $this->db->get_where('pr_printer', ['id' => $id])->row_array();
    }
    public function insert($data) {
        return $this->db->insert('pr_printer', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('pr_printer', $data);
    }

    public function delete($id) {
        return $this->db->delete('pr_printer', ['id' => $id]);
    }
    public function get_by_name($printer_name) {
    return $this->db->get_where('pr_printer', ['printer_name' => $printer_name])->row_array();
    }
    public function get_by_lokasi($lokasi_printer) {
        return $this->db->get_where('pr_printer', ['lokasi_printer' => $lokasi_printer])->row_array();
    }

}