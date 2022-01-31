<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Crud extends CI_Model
{
	public $table = 'berita';

	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	public function datatables()
	{
		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.uuid, a.judul, a.gambar, a.slug, a.konten,
			a.is_published, a.created_at, a.created_by,
			(SELECT b.nama FROM kategori AS b 
			WHERE b.id = a.kategori_id) AS nama_kategori 
			FROM berita AS a
            WHERE a.is_active = '1'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
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
		$data = $this->db->select('a.*, b.nama as nama_kategori')
			->join('kategori b', 'b.id = a.kategori_id')
			->get("{$this->table} a")->result();

		return $data;
	}

	public function insert($data = [])
	{
		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function get_where($where = [])
	{
		$data = $this->db->select('a.*')->get_where("{$this->table} a", $where)->row();
		return $data;
	}

	public function update($data = [], $where = [])
	{
		$update = $this->db->update($this->table, $data, $where);
		return $update;
	}

	public function num_rows($where)
	{
		$data = $this->db->select('a.*')->get_where("{$this->table} a", $where)->num_rows();
		return $data;
	}

	// public function delete($table, $id = null)
	// {
	//     return $this->db->delete($table, ['id' => $id]);
	// }
}
