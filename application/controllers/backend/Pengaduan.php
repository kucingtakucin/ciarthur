<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MY_Controller
{
	private $_path = 'backend/pengaduan/';
	private $_table = '';

	public function __construct()
	{
		parent::__construct();
		has_permission('access-pengaduan');
		$this->load->library('pusher');
	}

	public function index()
	{
		method('get');
		$this->templates->render([
			'title' => 'Pengaduan',
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', 'Pengaduan'
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script_js',
			'style' => 'contents/' . $this->_path . 'css/style_css',
			'modals' => []
		]);
	}

	public function data()
	{
		method('get');
		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.name, a.email, a.phone, a.message, a.created_at
            FROM pengaduan AS a
            WHERE a.is_active = '1'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		return $this->output->set_content_type('application/json')
			->set_output($datatables->generate());
	}

	public function chat($id)
	{
		method('get');
		$this->templates->render([
			'title' => 'Pengaduan / Chat',
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'chat/index',
			'script' => 'contents/' . $this->_path . 'chat/js/script_js',
			'style' => 'contents/' . $this->_path . 'chat/css/style_css',
			'modals' => []
		]);
	}
}
