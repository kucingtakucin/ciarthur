<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Config
		$this->_name = 'home';
		$this->_path = "frontend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

	}

	public function index()
	{
		$config = [
			'title' => 'Home',
			'type' => 'frontend',
			'uri_segment' => $this->_path,
			'header' => 'contents/' . $this->_path . 'header',
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => []
		];

		render($config);
	}
}
