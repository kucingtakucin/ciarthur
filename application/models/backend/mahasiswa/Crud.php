<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Crud extends CI_Model
{
	public $table = 'mahasiswa';

	public function get()
	{
		$data = $this->db->select('a.*, b.nama as nama_fakultas, c.nama as nama_prodi')
			->join('fakultas b', 'b.id = a.fakultas_id')
			->join('prodi c', 'c.id = a.prodi_id')
			->get("{$this->table} a")->result();
		return $data;
	}

	public function insert($data = [])
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		$data = $this->db->select('a.*, b.nama as nama_fakultas, c.nama as nama_prodi')
			->join('fakultas b', 'b.id = a.fakultas_id')
			->join('prodi c', 'c.id = a.prodi_id')
			->get_where("{$this->table} a", $where)->row();
		return $data;
	}

	public function update($data = [], $where = [])
	{
		$update = $this->db->update($this->table, $data, $where);
		return $update;
	}

	// public function delete($table, $id = null)
	// {
	//     return $this->db->delete($table, ['id' => $id]);
	// }
}
