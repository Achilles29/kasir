<?php
class Extra_model extends CI_Model {
    public function get_filtered($limit, $offset, $search = '') {
        $this->db->from('pr_produk_extra');
        if (!empty($search)) {
            $this->db->like('nama_extra', $search);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function count_filtered($search = '') {
        $this->db->from('pr_produk_extra');
        if (!empty($search)) {
            $this->db->like('nama_extra', $search);
        }
        return $this->db->count_all_results();
    }

    public function insert($data) {
        return $this->db->insert('pr_produk_extra', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('pr_produk_extra', $data);
    }

    public function delete($id) {
        return $this->db->delete('pr_produk_extra', ['id' => $id]);
    }

    public function get_by_id($id) {
        return $this->db->get_where('pr_produk_extra', ['id' => $id])->row_array();
    }
        public function getAllExtra() {
        return $this->db->get('pr_produk_extra')->result_array();
    }
}