<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Crud extends CI_Model
{
	private $_table = 'kategori';

	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	public function datatables()
	{
		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.nama, a.type, a.created_at FROM kategori AS a
            WHERE a.is_active = '1' AND a.type = '"
				. strtolower(explode(" ", get('type'))[1]) . "'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', fn () => 0);
		$datatables->add('encrypt_id', fn ($data) => urlencode($this->encryption->encrypt($data['id'])));

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
