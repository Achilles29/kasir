<?php
class Promo_voucher_model extends CI_Model
{
    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get('pr_promo_voucher_auto')->result();
    }
    

    public function get($id)
    {
        return $this->db->get_where('pr_promo_voucher_auto', ['id' => $id])->row_array();
    }

    public function simpan($data)
    {
        $id = $data['id'] ?? null;
    
        // Pastikan produk_ids disimpan sebagai angka/CSV string jika multiple
        if (isset($data['produk_ids'])) {
            // Jika dalam format string JSON dari JS
            if (is_array($data['produk_ids'])) {
                $data['produk_ids'] = implode(',', $data['produk_ids']);
            } elseif (is_string($data['produk_ids']) && str_starts_with($data['produk_ids'], '[')) {
                $decoded = json_decode($data['produk_ids'], true);
                $data['produk_ids'] = is_array($decoded) ? implode(',', $decoded) : '';
            }
        }
        
        unset($data['id']); // Jangan kirim ID ke insert/update
    
        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->update('pr_promo_voucher_auto', $data, ['id' => $id]);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('pr_promo_voucher_auto', $data);
        }
    
        return ['status' => 'ok'];
    }
    
    
}