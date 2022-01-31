<?php

class Datatable extends CI_Model
{
	private $_table = 'pengaduan';
	private $_column_order = [null, 'a.name', 'a.email', 'a.phone', null];
	private $_column_search = [null, 'a.name', 'a.email', 'a.phone', null];
	private $_default_order = ['id ' => 'DESC'];

	private function _query()
	{
		// Query
		$q =
			"SELECT a.uuid, a.id, a.name, a.email, a.phone, a.message, a.created_at
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
			$row['name'] = $v->name;
			$row['email'] = $v->email;
			$row['phone'] = $v->phone;
			$row['message'] = $v->message;
			$row['aksi'] = "
				<div role=\"group\" class=\"btn-group btn-group-sm\">
					<button type=\"button\" class=\"btn btn-danger btn_detail\" title=\"Detail Pengaduan\">
						<i class=\"fa fa-eye\"></i>
					</button>
					<button type=\"button\" class=\"btn btn-success btn_chat\" title=\"Respon Pengaduan\">
						<i class=\"fa fa-reply\"></i>
					</button>
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
			"csrf" => csrf()
		];
	}
}
