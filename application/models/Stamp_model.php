<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stamp_model extends CI_Model {

    public function get_all_promo() {
        return $this->db->order_by('created_at', 'DESC')->get('pr_promo_stamp')->result_array();
    }

    public function get_promo_by_id($id) {
        return $this->db->get_where('pr_promo_stamp', ['id' => $id])->row_array();
    }

    public function insert_promo($data) {
        $this->db->insert('pr_promo_stamp', $data);
        return $this->db->insert_id();
    }

    public function update_promo($id, $data) {
        $this->db->where('id', $id)->update('pr_promo_stamp', $data);
    }

    public function delete_promo($id) {
        return $this->db->delete('pr_promo_stamp', ['id' => $id]);
    }

    public function get_customer_stamp($customer_id) {
        return $this->db->get_where('pr_customer_stamp', ['customer_id' => $customer_id])->result_array();
    }

    public function get_stamp_log($customer_id) {
        return $this->db->get_where('pr_stamp_log', ['customer_id' => $customer_id])->result_array();
    }

    public function add_stamp_log($data) {
        return $this->db->insert('pr_stamp_log', $data);
    }

    public function update_customer_stamp($customer_id, $promo_id, $data) {
        $this->db->where(['customer_id' => $customer_id, 'promo_stamp_id' => $promo_id]);
        return $this->db->update('pr_customer_stamp', $data);
    }

    public function insert_customer_stamp($data) {
        return $this->db->insert('pr_customer_stamp', $data);
    }
}