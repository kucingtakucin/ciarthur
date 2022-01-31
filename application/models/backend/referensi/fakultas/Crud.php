<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crud extends CI_Model
{
	private $_table = 'fakultas';

	public function get()
	{
		$this->db->select('a.*');
		$this->db->from("{$this->_table} a");
		return $this->db->get()->result();
	}

	public function insert($data = [])
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		$this->db->select('a.*');
		$this->db->from("{$this->_table} a");
		$this->db->where($where);
		return $this->db->get()->row();
	}

	public function update($data = [], $where = [])
	{
		$this->db->where($where);
		return $this->db->update($this->_table, $data);
	}

	public function num_rows($where)
	{
		$this->db->select('a.*');
		$this->db->from("{$this->_table} a");
		$this->db->where($where);
		return $this->db->num_rows();
	}

	// public function delete($where = [])
	// {
	//     return $this->db->delete($this->_table, $where);
	// }
}
