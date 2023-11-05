<?php
defined('BASEPATH') OR exit('Akses script langsung tidak diizinkan');

class Template_model extends CI_Model
{
    protected $_table = 'tb_template_surat';
    protected $primary = 'id';

    public function getAll()
    {
        // Mengambil semua data dari tabel 'tb_template_surat' yang memiliki is_active = 1
        return $this->db->where('is_active', 1)->get($this->_table)->result();
    }

    public function save()
    {
        // Mendapatkan data dari inputan form
        $data = [
            'nama' => $this->input->post('no_surat'),
            'tujuan_surat' => $this->input->post('tgl_surat'),
            'tgl_kirim' => $this->input->post('surat_from'),
            'perihal' => $this->input->post('surat_to'),
            'keterangan' => $this->input->post('tgl_terima'),
            'is_active' => '1',
        ];

        // Menyimpan data ke dalam tabel 'tb_template_surat'
        $this->db->insert($this->_table, $data);
    }

    public function getById($id)
    {
        // Mengambil data berdasarkan ID
        return $this->db->get_where($this->_table, ["id" => $id])->row();
    }
}
?>
