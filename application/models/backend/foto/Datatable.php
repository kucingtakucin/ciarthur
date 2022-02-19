<?php

class Datatable extends CI_Model
{
	private $_table = 'foto AS a';
	private $_column_order = [null, null, 'a.judul', null, null];
	private $_column_search = [null, null, 'a.judul', null, null];
	private $_default_order = ['a.created_at ' => 'DESC'];

	private function _query()
	{
		$galeri_id = @$this->db->get_where('galeri', [
			'uuid' => post('uuid_galeri'),
			'is_active' => '1',
			'deleted_at' => null
		])->row()->id;

		if (!$galeri_id) $galeri_id = '0';

		// Query
		$q =
			"SELECT a.id, a.uuid, a.judul, a.galeri_id,
			a.gambar, a.is_published, a.created_at,

			CASE
				WHEN a.is_published = '1'
				THEN 'badge-success deactivate-foto'
				ELSE 'badge-danger activate-foto'
			END AS is_published_class,

			CASE
				WHEN a.is_published = '1'
				THEN 'Active'
				ELSE 'Inactive'
			END AS is_published_name
			
			FROM {$this->_table}
            WHERE 1=1
			AND a.is_active = '1'
			AND a.deleted_at IS NULL
			AND a.galeri_id = " . $galeri_id;

		if (session('tahun')) $q .= " AND YEAR(a.created_at) = " . session('tahun');

		// Records Total
		$return['recordsTotal'] = $this->db->query($q)->num_rows();
		// ========================================================================

		$q .= " HAVING 1=1 AND (1=0";
		$search_value = false;
		foreach ($this->_column_search as $k => $v) {
			if ($v && post("columns[$k][search][value]")) {
				$search_value = true;
				$q .= " OR {$v} LIKE '%" . post("columns[$k][search][value]") . "%'";
			} elseif ($v && post('search[value]')) {
				$search_value = true;
				$q .= " OR {$v} LIKE '%" . post('search[value]') . "%'";
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

		if (!is_null(post('start')) && post('length'))
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

		foreach ($result['data'] as $k => $v) {
			$row = [];
			$row['no'] = $no++;
			$row['uuid'] = $v->uuid;
			$row['judul_raw'] = $v->judul;
			$row['judul'] = "<span class=\"text-primary font-weight-bold data-foto\" data-uuid=\"{$v->uuid}\" style=\"cursor: pointer;\">$v->judul</span>";
			$row['is_published'] = "
				<a class=\"badge {$v->is_published_class}\" style=\"cursor: pointer;\" data-uuid=\"{$v->uuid}\">{$v->is_published_name}</a>
			";
			$gambar = base_url("img/berita/{$v->gambar}?w=150&h=150&fit=crop");
			$mime_type = @get_headers($gambar, true)['Content-Type'];
			$encoded = base64_encode(file_get_contents($gambar));
			$row['gambar'] = "
				<img src=\"data:$mime_type;base64,$encoded\" alt=\"Gambar {$v->judul}\">
			";

			$row['aksi'] = "
				<div role=\"group\" class=\"btn-group btn-group-sm\">
					" . (is_allowed('update-foto') ? "
					<button type=\"button\" class=\"btn btn-success btn_edit\" data-uuid=\"{$v->uuid}\" title=\"Ubah Data\">
						<i class=\"fa fa-edit\"></i>
					</button>" : "") . (is_allowed('delete-foto') ? " 
					<button type=\"button\" class=\"btn btn-danger btn_delete\" data-uuid=\"{$v->uuid}\" title=\"Hapus Data\">
						<i class=\"fa fa-trash\"></i>
					</button> " : "") . "
				</div>
			";
			$row['created_at'] = $v->created_at;
			$data[$k] = $row;
		}

		return [
			"draw" => post('draw'),
			"recordsTotal" => $result['recordsTotal'],
			"recordsFiltered" => $result['recordsFiltered'],
			"data" => $data,
			"query" => $result['query'],
			"payload" => post()
		];
	}
}
