<?php

class M_NamaModel extends CI_Model
{
    public $table = 'nama_tabel';

    public function get()
    {
        return $this->db->select('a.*, b.nama as nama_apa_b, c.nama as nama_apa_c')
            ->join('apa_b b', 'b.id = a.apa_b_id')
            ->join('apa_c c', 'c.id = a.prodi_id')
            ->get("{$this->table} a")->result();
    }

    public function insert($data = [])
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_where($where = [])
    {
        return $this->db->select('a.*, b.nama as nama_apa_b, c.nama as nama_apa_c')
            ->join('apa_b b', 'b.id = a.apa_b_id')
            ->join('apa_c c', 'c.id = a.apa_c_id')
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
