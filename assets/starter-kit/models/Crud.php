<?php

class Crud extends CI_Model
{
	private $_table = 'nama_tabel';

	//=============================================================//
	//=========================== CRUD ============================//
	//=============================================================//

	public function get()
	{
		$this->db->select('a.*, b.nama as nama_apa_b, c.nama as nama_apa_c');
		$this->db->join('apa_b b', 'b.id = a.apa_b_id');
		$this->db->join('apa_c c', 'c.id = a.apa_c_id');
		return $this->db->get("{$this->_table} a")->result();
	}

	public function insert($data = [])
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		$this->db->select('a.*, b.nama as nama_apa_b, c.nama as nama_apa_c');
		$this->db->join('apa_b b', 'b.id = a.apa_b_id');
		$this->db->join('apa_c c', 'c.id = a.apa_c_id');
		return $this->db->get_where("{$this->_table} a", $where)->row();
	}

	public function update($data = [], $where = [])
	{
		return $this->db->update($this->_table, $data, $where);
	}

	public function num_rows($where)
	{
		$this->db->select('a.*');
		$this->db->from("{$this->_table} a");
		$this->db->where($where);
		return $this->db->num_rows();
	}

	// public function delete($where)
	// {
	//     return $this->db->delete($this->_table, $where);
	// }
}
