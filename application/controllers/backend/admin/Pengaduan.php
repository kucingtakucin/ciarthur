<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MY_Controller
{
	private $_path = 'backend/admin/pengaduan/';
	private $_table = '';

	public function __construct()
	{
		parent::__construct();
		check_group("admin");
		$this->load->library('pusher');
	}

	public function index()
	{
		$this->templates->render([
			'title' => 'Pengaduan',
			'type' => 'backend',
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script_js',
			'style' => 'contents/' . $this->_path . 'css/style_css',
			'modals' => []
		]);
	}

	public function coba()
	{
		if ($this->input->method() == 'post') {
			$pusher = $this->pusher->get_pusher();
			$pusher->trigger('ciarthur-pengaduan-channel', 'ciarthur-pengaduan-event', [
				'message' => 'Ini adalah uji coba'
			]);
		}
	}
}
