<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

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

	public function data()
	{
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

	public function reply($id)
	{
		$decrypted_id = $this->encryption->decrypt($id);
		echo $decrypted_id;
	}
}
