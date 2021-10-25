<?php

class M_Berita extends CI_Model
{
	public $table = 'berita';

	public function get()
	{
		return $this->db->select('a.*, b.nama as nama_kategori')
			->join('kategori b', 'b.id = a.kategori_id')
			->get("{$this->table} a")->result();
	}

	public function insert($data = [])
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		return $this->db->select('a.*, b.nama as nama_kategori')
			->join('kategori b', 'b.id = a.kategori_id')
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
