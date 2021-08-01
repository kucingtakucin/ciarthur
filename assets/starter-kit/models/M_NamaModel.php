<?php

class M_NamaModel extends CI_Model
{
    private $table = 'nama_table'; // nama table

    public function insert($data = [])
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_where($where = [])
    {
        return $this->db->get_where($this->table, $where)->row();
    }

    public function update($data = [], $id = null)
    {
        return $this->db->update("{$this->table} a", $data, ['a.id' => $id]);
    }

    // public function delete($table, $id = null)
    // {
    //     return $this->db->delete($table, ['id' => $id]);
    // }
}
