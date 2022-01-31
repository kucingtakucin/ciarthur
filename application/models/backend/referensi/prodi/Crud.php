<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Crud extends CI_Model
{
	private $_table = 'prodi';

	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	public function datatables()
	{
		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.uuid, a.nama, a.fakultas_id,
            (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
            a.created_at FROM prodi AS a
            WHERE a.is_active = '1'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		$datatables->add('encrypt_id', function ($data) {
			return urlencode($this->encryption->encrypt($data['id']));
		});

		$result = $datatables->generate()->toArray();

		// For dev purposes
		$result['last_query'] = $datatables->getQuery();

		return $result;
	}

	//=============================================================//
	//=========================== CRUD ============================//
	//=============================================================//

	public function get()
	{
		$data = $this->db->query(
			"SELECT a.id, a.nama, a.fakultas_id,
            (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
            a.created_at FROM $this->_table AS a
            WHERE a.is_active = '1'"
		)->result();
		return $data;
	}

	public function insert($data = [])
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		$query = "SELECT a.id, a.nama, a.fakultas_id,
        (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
        a.created_at FROM {$this->_table} AS a
        WHERE a.is_active = '1'";

		foreach ($where as $key => $value) {
			$query .= " AND $key = " . is_int($value) ? $value : (is_string($value) ? "'$value'" : '');
		}

		$data = $this->db->query($query)->get_where("{$this->_table} a", $where)->row();
		return $data;
	}

	public function update($data = [], $where = [])
	{
		$update = $this->db->update($this->_table, $data, $where);
		return $update;
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
