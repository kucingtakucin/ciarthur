<?php

class M_Kategori extends CI_Model
{
	public $table = 'kategori';

	public function get()
	{
		return $this->db->select('a.*')
			->get("{$this->table} a")->result();
	}

	public function insert($data = [])
	{
		return $this->db->insert($this->table, $data);
	}

	public function get_where($where = [])
	{
		return $this->db->select('a.*')
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
