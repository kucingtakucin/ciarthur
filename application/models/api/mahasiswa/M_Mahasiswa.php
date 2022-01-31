<?php

class M_Mahasiswa extends CI_Model
{
	public $table = 'mahasiswa';

	public function get()
	{
		$this->db->trans_begin();
		$data = $this->db->get($this->table)->result();
		if (!$this->db->trans_status() || !$data) {
			$this->db->trans_rollback();
		}
		$this->db->trans_commit();
		return $data;
	}

	public function insert($data = [])
	{
		$this->db->trans_begin();
		$insert = $this->db->insert($this->table, $data);
		if (!$this->db->trans_status() || !$insert) {
			$this->db->trans_rollback();
			return false;
		}
		$this->db->trans_commit();
		return $this->db->insert_id();
	}

	public function get_where($where = [])
	{
		$this->db->trans_begin();
		$data = $this->db->select('a.*, b.nama as nama_fakultas, c.nama as nama_prodi')
			->join('fakultas b', 'b.id = a.fakultas_id')
			->join('prodi c', 'c.id = a.prodi_id')
			->get_where("{$this->table} a", $where)->row();
		if (!$this->db->trans_status() || !$data) {
			$this->db->trans_rollback();
		}
		$this->db->trans_commit();
		return $data;
	}

	public function update($data = [], $where = [])
	{
		$this->db->trans_begin();
		$update = $this->db->update($this->table, $data, $where);
		if (!$this->db->trans_status() || !$update) {
			$this->db->trans_rollback();
			return false;
		}
		$this->db->trans_commit();
		return $update;
	}

	// public function delete($table, $id = null)
	// {
	//     return $this->db->delete($table, ['id' => $id]);
	// }
}
