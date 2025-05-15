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

    public function simpan($post)
    {
        $id = $post['id'] ?? null;
    
        $produk_trigger = $post['produk_trigger'] ?? null;

    
        $data = [
            'nama_promo'        => $post['nama_promo'] ?? null,
            'tipe_trigger'      => $post['tipe_trigger'] ?? null,
            'nilai'             => $post['nilai'] ?? 0,
            'produk_trigger'    => $post['produk_trigger'] ?? null,
            'masa_berlaku'      => $post['masa_berlaku'] ?? null,
            'jenis'             => $post['jenis'] ?? null,
            'produk_id'         => $post['produk_id'] ?? null,
            'nilai_voucher'     => $post['nilai_voucher'] ?? 0,
            'min_pembelian'     => $post['min_pembelian'] ?? 0,
            'max_diskon'        => $post['max_diskon'] ?? 0,
            'maksimal_voucher'  => $post['maksimal_voucher'] ?? 0,
            'aktif'             => $post['aktif'] ?? 0,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
        
        if ($produk_trigger === null) {
            unset($data['produk_trigger']);
        }
    
        if ($id) {
            $this->db->update('pr_promo_voucher_auto', $data, ['id' => $id]);
        } else {
            $data['created_at'] = $data['updated_at'];
            $this->db->insert('pr_promo_voucher_auto', $data);
        }
    
        return ['status' => 'ok'];
    }
    
    
    
    
}