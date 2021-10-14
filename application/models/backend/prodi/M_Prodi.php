<?php

class M_Prodi extends CI_Model
{
    public $table = 'prodi';

    public function get()
    {
        return $this->db->query(
            "SELECT a.id, a.nama, a.fakultas_id,
            (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
            a.created_at FROM $this->table AS a
            WHERE a.is_active = '1'"
        )->result();
    }

    public function insert($data = [])
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_where($where = [])
    {
        $query = "SELECT a.id, a.nama, a.fakultas_id,
        (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
        a.created_at FROM $this->table AS a
        WHERE a.is_active = '1'";

        foreach ($where as $key => $value) {
            $query .= " AND $key = " . is_int($value) ? $value : (is_string($value) ? "'$value'" : '');
        }

        return $this->db->query($query)->get_where("{$this->table} a", $where)->row();
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
