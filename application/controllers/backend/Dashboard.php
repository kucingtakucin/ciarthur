<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'dashboard';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

		has_permission("access-{$this->_name}");
	}

	public function index()
	{
		method('get');
		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend',
			'breadcrumb' => [
				'Backend', ucwords($this->_name)
			],
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => []
		];

		render($config);
	}

	public function session()
	{
		method('get');
		role("admin");

		dd(session());
	}
}
