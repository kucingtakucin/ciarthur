<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'pengaduan';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

		has_permission("access-{$this->_name}");

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
		// $this->load->model($this->_path . 'Crud');   // Load CRUD model
	}

	public function index()
	{
		method('get');

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', ucwords($this->_name)
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => []
		];

		render($config);
	}

	public function data()
	{
		method('post');

		response($this->Datatable->list());
	}

	public function chat($uuid = null)
	{
		method('get');

		$config = [
			'title' => 'Pengaduan / Chat',
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', 'Pengaduan', 'Chat'
			],
			'page' => 'contents/' . $this->_path . 'chat/index',
			'script' => 'contents/' . $this->_path . 'chat/js/script.js.php',
			'style' => 'contents/' . $this->_path . 'chat/css/style.css.php',
			'modals' => []
		];

		render($config);
	}
}
