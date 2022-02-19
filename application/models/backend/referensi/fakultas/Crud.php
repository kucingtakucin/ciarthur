<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crud extends CI_Model
{
	private $_table = 'fakultas';

	// Query SQL
	private function _query($where = [], $having = [])
	{
		$q = "SELECT a.*
			FROM {$this->_table} a
			WHERE 1=1 
			AND a.is_active = '1'
			AND a.deleted_at IS NULL";

		if (count($where))
			foreach ($where as $k => $v) {
				$q .= " AND $k = " .
					(is_string($v) ? "'$v'"			// Jika string
						: (is_int($v) ? "$v"		// Jika integer
							: (is_null($v) ? "NULL"	// Jika null
								: "'$v'")));
			}

		if (count($having)) {
			$q .= " HAVING 1=1 AND (1=0";
			foreach ($having as $k => $v) {
				$q .= " OR {$k} LIKE '%" . $v . "%'";
			}
			$q .= " )";
		}

		return $q;
	}

	// Get all data
	public function get()
	{
		$q = $this->_query();

		return $this->db->query($q)->result();
	}

	// Get data by where clause, returning multiple records
	public function get_where($where = [])
	{
		$q = $this->_query($where);

		return $this->db->query($q)->result();
	}

	// Get data by where clause, returning single record
	public function detail($where = [])
	{
		$q = $this->_query($where);

		return $this->db->query($q)->row();
	}

	// Insert data
	public function insert($data = [])
	{
		$this->db->insert($this->_table, $data);

		return $this->db->insert_id();
	}

	// Update data and soft delete
	public function update($data = [], $where = [])
	{
		return $this->db->update($this->_table, $data, $where);
	}

	// Get num rows
	public function num_rows($where = [])
	{
		$q = $this->_query($where);

		return $this->db->query($q)->num_rows();
	}

	// Delete data
	// public function delete($where)
	// {
	//     return $this->db->delete($this->_table, $where);
	// }
}
