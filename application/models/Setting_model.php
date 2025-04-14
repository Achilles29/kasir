<?php
class Setting_model extends CI_Model
{
    public function get_all_printer()
    {
        return $this->db->get('pr_printer')->result();
    }
    public function get_printer($id)
    {
        return $this->db->get_where('pr_printer', ['id' => $id])->row_array();
    }

    public function get_data_struk()
    {
        return $this->db->get('pr_struk')->row_array();
    }

    public function get_tampilan_struk($printer_id)
    {
        return $this->db->get_where('pr_struk_tampilan', ['printer_id' => $printer_id])->row_array();
    }

    // Simpan data perusahaan (plus logo jika ada)
    public function simpan_data_struk($data)
    {
        // Handle upload logo jika ada
        if (!empty($_FILES['logo']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['file_name']     = 'logo_' . time();
            $config['overwrite']     = true;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('logo')) {
                $data['logo'] = $this->upload->data('file_name');
            }
        }

        // Cek data sudah ada atau belum
        $cek = $this->get_data_struk();
        if ($cek) {
            $this->db->update('pr_struk', $data);
        } else {
            $this->db->insert('pr_struk', $data);
        }
    }

    // Simpan pengaturan tampilan struk per lokasi
    public function simpan_tampilan_struk($data)
    {
        $cek = $this->get_tampilan_struk($data['printer_id']);
        if ($cek) {
            $this->db->where('printer_id', $data['printer_id'])->update('pr_struk_tampilan', $data);
        } else {
            $this->db->insert('pr_struk_tampilan', $data);
        }
    }
}