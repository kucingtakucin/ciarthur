<?php

class M_Pengaduan extends CI_Model
{
    public $table = 'pengaduan';

    public function insert($data = [])
    {
        return $this->db->insert($this->table, $data);
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
