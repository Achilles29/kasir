    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class PurchasePending_model extends CI_Model {
        public function get_by_date_range($start_date, $end_date, $limit, $offset) {
        $this->db->select('
            bl_purchase_pending.*, 
            bl_db_belanja.nama_barang, 
            bl_db_belanja.nama_bahan_baku, 
            bl_kategori.nama_kategori, 
            bl_tipe_produksi.nama_tipe_produksi,
            bl_db_purchase.merk,
            bl_db_purchase.keterangan,
            bl_db_purchase.ukuran,
            bl_db_purchase.unit,
            bl_db_purchase.pack,
            bl_db_purchase.harga_satuan,
            bl_rekening.nama_rekening AS metode_pembayaran
        ');
        $this->db->from('bl_purchase_pending');
        $this->db->join('bl_db_purchase', 'bl_purchase_pending.bl_db_purchase_id = bl_db_purchase.id', 'left');
        $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
        $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
        $this->db->join('bl_rekening', 'bl_purchase_pending.metode_pembayaran = bl_rekening.id', 'left');

            $this->db->where('bl_purchase_pending.tanggal_pembelian >=', $start_date);
            $this->db->where('bl_purchase_pending.tanggal_pembelian <=', $end_date);
            $this->db->limit($limit, $offset);
            return $this->db->get()->result_array();
        }

        public function insert($data) {
            if (!isset($data['nama_barang']) || !isset($data['kuantitas'])) {
                throw new Exception('Data tidak lengkap untuk insert.');
            }

            return $this->db->insert('bl_purchase_pending', $data);
        }

    public function get_all($start_date, $end_date, $limit, $offset) {
        $this->db->select('
            bl_purchase_pending.*,
            bl_kategori.nama_kategori AS kategori,
            bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
            bl_jenis_pengeluaran.nama_jenis_pengeluaran AS jenis_pengeluaran,
            bl_rekening.nama_rekening AS metode_pembayaran
        ');
        $this->db->from('bl_purchase_pending');
        $this->db->join('bl_kategori', 'bl_purchase_pending.kategori_id = bl_kategori.id', 'left');
        $this->db->join('bl_tipe_produksi', 'bl_purchase_pending.tipe_produksi_id = bl_tipe_produksi.id', 'left');
        $this->db->join('bl_jenis_pengeluaran', 'bl_purchase_pending.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
        $this->db->join('bl_rekening', 'bl_purchase_pending.metode_pembayaran = bl_rekening.id', 'left');
        $this->db->where('bl_purchase_pending.tanggal_pembelian >=', $start_date);
        $this->db->where('bl_purchase_pending.tanggal_pembelian <=', $end_date);
        $this->db->limit($limit, $offset);
        $this->db->order_by('bl_purchase_pending.tanggal_pembelian', 'DESC');

        return $this->db->get()->result_array();
    }


    public function count_filtered($start_date, $end_date) {
        $this->db->from('bl_purchase_pending');
        $this->db->where('tanggal_pembelian >=', $start_date);
        $this->db->where('tanggal_pembelian <=', $end_date);
        return $this->db->count_all_results();
    }
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('bl_purchase_pending')->row_array();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('bl_purchase_pending');
    }
        

    }
