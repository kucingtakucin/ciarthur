<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	private $_path = 'backend/member/dashboard/';
	private $_table = '';

	public function __construct()
	{
		parent::__construct();
		check_group("member");
	}

	public function index()
	{
		$this->templates->render([
			'title' => 'Dashboard',
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script_js',
			'style' => 'contents/' . $this->_path . 'css/style_css',
			'modals' => []
		]);
	}
}
