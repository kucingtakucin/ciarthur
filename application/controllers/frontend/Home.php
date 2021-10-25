<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
	private $_path = 'frontend/home/';
	private $_table = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->templates->render([
			'title' => 'Home',
			'type' => 'frontend',
			'uri_segment' => $this->_path,
			'header' => 'contents/' . $this->_path . 'header',
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => []
		]);
	}
}
