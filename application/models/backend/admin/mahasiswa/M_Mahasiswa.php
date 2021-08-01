<?php

class M_Mahasiswa extends CI_Model
{
    public $table = 'mahasiswa';

    public function insert($data = [])
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_where($where = [])
    {
        return $this->db->select('a.*, b.nama as nama_fakultas, c.nama as nama_prodi')
            ->join('fakultas b', 'b.id = a.fakultas_id')
            ->join('prodi c', 'c.id = a.prodi_id')
            ->get_where("{$this->table} a", $where)->row();
    }

    public function update($data = [], $id = null)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    // public function delete($table, $id = null)
    // {
    //     return $this->db->delete($table, ['id' => $id]);
    // }
}
