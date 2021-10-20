<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	private $_path = 'backend/dashboard/';
	private $_table = '';

	public function __construct()
	{
		parent::__construct();
		has_permission('access-dashboard');
	}

	public function index()
	{
		method('get');
		$this->templates->render([
			'title' => 'Dashboard',
			'type' => 'backend',
			'breadcrumb' => [
				'Backend', 'Dashboard'
			],
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script_js',
			'style' => 'contents/' . $this->_path . 'css/style_css',
			'modals' => []
		]);
	}
}
