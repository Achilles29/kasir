<?php
class Setting_model extends CI_Model
{
    public function get_all_divisi()
    {
        return $this->db->get('pr_divisi')->result();
    }

    public function get_all_struk_tampilan()
    {
        return $this->db->get('pr_struk_tampilan')->result();
    }

    public function get_data_struk()
    {
        return $this->db->get('pr_struk')->row_array();
    }

    public function get_divisi($id)
    {
        return $this->db->get_where('pr_divisi', ['id' => $id])->row_array();
    }

    public function get_tampilan_struk($divisi_id)
    {
        return $this->db->get_where('pr_struk_tampilan', ['pr_divisi_id' => $divisi_id])->row_array();
    }

public function simpan_data_struk($data)
{
if (!empty($_FILES['logo']['name'])) {
    $config['upload_path'] = './uploads/';
    $config['allowed_types'] = 'jpg|jpeg|png|webp';
    $config['file_name'] = 'logo_' . time();

    $this->load->library('upload', $config);
    if ($this->upload->do_upload('logo')) {
        $data['logo'] = $this->upload->data('file_name');
    }
}


    $cek = $this->get_data_struk();
    if ($cek) {
        $this->db->update('pr_struk', $data);
    } else {
        $this->db->insert('pr_struk', $data);
    }
}

    public function simpan_tampilan_struk($data)
    {
        $cek = $this->get_tampilan_struk($data['pr_divisi_id']);
        if ($cek) {
            $this->db->where('pr_divisi_id', $data['pr_divisi_id'])->update('pr_struk_tampilan', $data);
        } else {
            $this->db->insert('pr_struk_tampilan', $data);
        }
    }
}
