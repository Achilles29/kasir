<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cost_Production_model extends CI_Model {

    public function get_cost_production_data($bulan) {
        // Start and end dates for the selected month
        $start_date = $bulan . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));

        // Initialize the data array to store results for each date
        $dates_in_month = [];
        for ($date = strtotime($start_date); $date <= strtotime($end_date); $date += 86400) {
            $formatted_date = date('Y-m-d', $date);
            $dates_in_month[$formatted_date] = [
                'tanggal' => $formatted_date,
                'cost_bar' => 0,
                'cost_kitchen' => 0,
                'cost_event' => 0,
                'purchase_bar' => 0,
                'purchase_kitchen' => 0,
                'purchase_event' => 0,
                'store_request_bar' => 0,
                'store_request_kitchen' => 0,
                'store_request_event' => 0,
                'revenue_bar' => 0,
                'revenue_kitchen' => 0,
                'revenue_event' => 0,
                'total_revenue' => 0,
                'percent_bar' => 0,
                'percent_kitchen' => 0,
                'percent_event' => 0,
                'percent_total' => 0
            ];
        }

        // Query for Purchase Cost calculation (purchase)
        $this->db->select('
            DATE(bl_purchase.tanggal) AS tanggal,
            SUM(CASE WHEN bl_purchase.jenis_pengeluaran = 2 AND bl_db_belanja.id_tipe_produksi = 1 THEN bl_purchase.total_harga ELSE 0 END) AS purchase_bar,
            SUM(CASE WHEN bl_purchase.jenis_pengeluaran = 3 AND bl_db_belanja.id_tipe_produksi = 1 THEN bl_purchase.total_harga ELSE 0 END) AS purchase_kitchen,
            SUM(CASE WHEN bl_purchase.jenis_pengeluaran = 5 AND bl_db_belanja.id_tipe_produksi = 1 THEN bl_purchase.total_harga ELSE 0 END) AS purchase_event
        ');
        $this->db->from('bl_purchase');
        $this->db->join('bl_db_belanja', 'bl_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->where('bl_purchase.tanggal >=', $start_date);
        $this->db->where('bl_purchase.tanggal <=', $end_date);
        $this->db->where('bl_db_belanja.id_tipe_produksi', 1); // Filter by tipe_produksi = 1
        $this->db->group_by('bl_purchase.tanggal');
        $query = $this->db->get();
        $result_purchase = $query->result_array();

        // Populate dates_in_month with purchase cost data
        foreach ($result_purchase as $row) {
            $dates_in_month[$row['tanggal']]['purchase_bar'] = $row['purchase_bar'];
            $dates_in_month[$row['tanggal']]['purchase_kitchen'] = $row['purchase_kitchen'];
            $dates_in_month[$row['tanggal']]['purchase_event'] = $row['purchase_event'];
        }

        // Query for Store Request Cost calculation (store request)
        $this->db->select('
            bl_store_request.tanggal AS tanggal,
            SUM(CASE WHEN bl_store_request.jenis_pengeluaran = 2 THEN (bl_db_purchase.harga_satuan * bl_store_request.kuantitas) ELSE 0 END) AS store_request_bar,
            SUM(CASE WHEN bl_store_request.jenis_pengeluaran = 3 THEN (bl_db_purchase.harga_satuan * bl_store_request.kuantitas) ELSE 0 END) AS store_request_kitchen,
            SUM(CASE WHEN bl_store_request.jenis_pengeluaran = 5 THEN (bl_db_purchase.harga_satuan * bl_store_request.kuantitas) ELSE 0 END) AS store_request_event
        ');
        $this->db->from('bl_store_request');
        $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
        $this->db->where('bl_store_request.tanggal >=', $start_date);
        $this->db->where('bl_store_request.tanggal <=', $end_date);
        $this->db->group_by('bl_store_request.tanggal');
        $query = $this->db->get();
        $result_store_request = $query->result_array();

        // Populate dates_in_month with store request cost data
        foreach ($result_store_request as $row) {
            $dates_in_month[$row['tanggal']]['store_request_bar'] = $row['store_request_bar'];
            $dates_in_month[$row['tanggal']]['store_request_kitchen'] = $row['store_request_kitchen'];
            $dates_in_month[$row['tanggal']]['store_request_event'] = $row['store_request_event'];
        }

        // Calculate the total cost by adding purchase and store request costs
        foreach ($dates_in_month as $date => &$data) {
            $data['cost_bar'] = $data['purchase_bar'] + $data['store_request_bar'];
            $data['cost_kitchen'] = $data['purchase_kitchen'] + $data['store_request_kitchen'];
            $data['cost_event'] = $data['purchase_event'] + $data['store_request_event'];
        }

        // For each date, calculate revenue for Bar, Kitchen, Event
        foreach ($dates_in_month as $date => &$data) {
            // Get revenue for Bar (Divisi 1)
            $this->db->select('SUM(bl_penjualan_produk.penjualan) AS revenue');
            $this->db->from('bl_penjualan_produk');
            $this->db->join('bl_produk', 'bl_penjualan_produk.sku = bl_produk.sku');
            $this->db->join('bl_divisi', 'bl_produk.divisi_id = bl_divisi.id');
            $this->db->where('bl_penjualan_produk.tanggal', $date);
            $this->db->where('bl_divisi.id', 1); // Bar Division
            $revenue_query_bar = $this->db->get();
            $data['revenue_bar'] = $revenue_query_bar->row()->revenue ?? 0;

            // Get revenue for Kitchen (Divisi 2)
            $this->db->select('SUM(bl_penjualan_produk.penjualan) AS revenue');
            $this->db->from('bl_penjualan_produk');
            $this->db->join('bl_produk', 'bl_penjualan_produk.sku = bl_produk.sku');
            $this->db->join('bl_divisi', 'bl_produk.divisi_id = bl_divisi.id');
            $this->db->where('bl_penjualan_produk.tanggal', $date);
            $this->db->where('bl_divisi.id', 2); // Kitchen Division
            $revenue_query_kitchen = $this->db->get();
            $data['revenue_kitchen'] = $revenue_query_kitchen->row()->revenue ?? 0;

            // Get revenue for Event (Divisi 5)
            $this->db->select('SUM(bl_penjualan_produk.penjualan) AS revenue');
            $this->db->from('bl_penjualan_produk');
            $this->db->join('bl_produk', 'bl_penjualan_produk.sku = bl_produk.sku');
            $this->db->join('bl_divisi', 'bl_produk.divisi_id = bl_divisi.id');
            $this->db->where('bl_penjualan_produk.tanggal', $date);
            $this->db->where('bl_divisi.id', 5); // Event Division
            $revenue_query_event = $this->db->get();
            $data['revenue_event'] = $revenue_query_event->row()->revenue ?? 0;

            // Calculate total revenue (revenue without refund)
            $this->db->select('SUM(bl_penjualan_produk.penjualan) AS total_revenue');
            $this->db->from('bl_penjualan_produk');
            $this->db->where('bl_penjualan_produk.tanggal', $date);
            $total_revenue_query = $this->db->get();
            $data['total_revenue'] = $total_revenue_query->row()->total_revenue ?? 0;

            // Calculate percentage for each division and total
            $data['percent_bar'] = $data['revenue_bar'] != 0 ? ($data['cost_bar']) / $data['revenue_bar'] * 100 : 0;
            $data['percent_kitchen'] = $data['revenue_kitchen'] != 0 ? ($data['cost_kitchen']) / $data['revenue_kitchen'] * 100 : 0;
            $data['percent_event'] = $data['revenue_event'] != 0 ? ($data['cost_event']) / $data['revenue_event'] * 100 : 0;
            $data['percent_total'] = $data['total_revenue'] != 0 ? ($data['cost_bar'] + $data['cost_kitchen'] + $data['cost_event']) / $data['total_revenue'] * 100 : 0;
        }

        return $dates_in_month;
    }
}
