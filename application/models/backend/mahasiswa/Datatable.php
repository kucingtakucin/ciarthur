<?php

class Datatable extends CI_Model
{
	private $_table = 'mahasiswa';
	private $_column_order = [null, null, 'nim', 'nama', 'nama_prodi', 'nama_fakultas', 'angkatan', null, null];
	private $_column_search = [null, null, 'nim', 'nama', 'nama_prodi', 'nama_fakultas', 'angkatan', null, null];
	private $_default_order = ['id ' => 'DESC'];

	private function _query()
	{
		// Query
		$q =
			"SELECT a.id, a.uuid, a.nim, a.nama, a.angkatan, a.foto,
            (SELECT b.nama FROM prodi AS b WHERE b.id = a.prodi_id) AS nama_prodi,
            (SELECT c.nama FROM fakultas AS c WHERE c.id = a.fakultas_id) AS nama_fakultas,
            a.created_at, a.prodi_id, a.fakultas_id, a.latitude, a.longitude
            FROM {$this->_table} AS a
            WHERE 1=1
			AND a.is_active = '1'
			AND a.deleted_at IS NULL";

		if (session('tahun')) $q .= " AND YEAR(a.created_at) = " . session('tahun');

		// Records Total
		$return['recordsTotal'] = $this->db->query($q)->num_rows();
		// ========================================================================

		$q .= " HAVING 1=1 AND (1=0";
		$search_value = false;
		foreach ($this->_column_search as $k => $v) {
			if ($v && post('columns')[$k]['search']['value']) {
				$search_value = true;
				$q .= " OR {$v} LIKE '%" . post('columns')[$k]['search']['value'] . "%'";
			} elseif ($v && post('search')['value']) {
				$search_value = true;
				$q .= " OR {$v} LIKE '%" . post('search')['value'] . "%'";
			}
		}

		if ($search_value) $q .= " )";
		else $q .= " OR 1=1)";

		// Records Filtered
		$return['recordsFiltered'] = $this->db->query($q)->num_rows();
		// ========================================================================

		if (!post('order')) $q .= " ORDER BY " . key($this->_default_order) .
			"{$this->_default_order[key($this->_default_order)]}";
		else {
			$q .= " ORDER BY";
			foreach (post('order') as $k => $v) {
				$q .= " {$this->_column_order[$v['column']]} {$v['dir']}";
				if ($k !== count(post('order')) - 1) $q .= ', ';
			}
		}

		if (post('start') && post('length'))
			$q .= " LIMIT " . post('start') . ", " . post('length');

		// Data
		$return['data'] = $this->db->query($q)->result();

		// Query
		$return['query'] = $this->db->last_query();
		return $return;
	}


	public function list()
	{
		$result = $this->_query();
		$no = post('start') + 1;
		$data = [];

		foreach ($result['data'] as  $k => $v) {
			$row = [];
			$row['no'] = $no++;
			$row['uuid'] = $v->uuid;
			$row['id'] = base64_encode($this->encryption->encrypt($v->id));
			$row['nim'] = $v->nim;
			$row['nama'] = $v->nama;
			$row['angkatan'] = $v->angkatan;
			$row['foto'] = $v->foto;
			$row['nama_prodi'] = $v->nama_prodi;
			$row['nama_fakultas'] = $v->nama_fakultas;
			$row['latitude'] = $v->latitude;
			$row['longitude'] = $v->longitude;
			$row['aksi'] = "
				<div role=\"group\" class=\"btn-group btn-group-sm\">
					<button type=\"button\" class=\"btn btn-success btn_edit\" data-uuid=\"{$v->uuid}\" data-id=\"" . base64_encode($this->encryption->encrypt($v->id)) . "\" title=\"Ubah Data\">
						<i class=\"fa fa-edit\"></i>
					</button>
					<button type=\"button\" class=\"btn btn-danger btn_delete\" data-uuid=\"{$v->uuid}\" data-id=\"" . base64_encode($this->encryption->encrypt($v->id)) . "\" title=\"Hapus Data\">
						<i class=\"fa fa-trash\"></i>
					</button>
				</div>
			";
			$row['prodi_id'] = $v->prodi_id;
			$row['fakultas_id'] = $v->fakultas_id;
			$row['created_at'] = $v->created_at;
			$data[$k] = $row;
		}

		return [
			"draw" => post('draw'),
			"recordsTotal" => $result['recordsTotal'],
			"recordsFiltered" => $result['recordsFiltered'],
			"data" => $data,
			"query" => $result['query'],
			"csrf" => csrf()
		];
	}
}
