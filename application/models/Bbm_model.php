<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bbm_model extends CI_Model
{
    public function get_all_spbu()
    {
        return $this->db->get('spbu')->result_array(); // asumsikan ada tabel `spbu`
    }

    public function get_spbu($id)
    {
        return $this->db->get_where('spbu', ['id' => $id])->row_array();
    }
    
public function generate_struk_bbm($spbu)
{
    $width = 32;
    $out = "";
    $out .= $this->center_text("[ STRUK SPBU ]", $width) . "\n";
    $out .= str_repeat("-", $width) . "\n";
    $out .= $this->format_struk_line("Kode", $spbu['kode'], $width) . "\n";
    $out .= $this->format_struk_line("Nama", $spbu['nama'], $width) . "\n";
    $out .= "Alamat:\n";
    $lines = explode("\n", wordwrap($spbu['alamat'], $width));
    foreach ($lines as $line) {
        $out .= $line . "\n";
    }
    $out .= str_repeat("-", $width) . "\n";
    $out .= $this->center_text("Dicetak: " . date('d-m-Y H:i'), $width) . "\n";
    $out .= "\n\n\n";
    return $out;
}

private function center_text($text, $width)
{
    $pad = max(0, floor(($width - strlen($text)) / 2));
    return str_repeat(" ", $pad) . $text;
}

private function format_struk_line($left, $right, $width)
{
    $space = max(1, $width - strlen($left) - strlen($right));
    return $left . str_repeat(' ', $space) . $right;
}


}