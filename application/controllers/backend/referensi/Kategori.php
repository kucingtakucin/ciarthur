<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends MY_Controller
{
	private $_path = 'backend/referensi/kategori/'; // Contoh 'backend/dashboard/ / 'frontend/home/'

	/**
	 * Kategori constructor
	 */
	public function __construct()
	{
		parent::__construct();
		has_permission('access-kategori');
		//=========================================================//

		$this->load->model($this->_path . 'M_Kategori');   // Load model
	}

	/**
	 * Halaman index
	 *
	 */
	public function index($type)
	{
		method('get');
		//=========================================================//        
		redirect($this->_path . "manage/$type");
	}

	public function manage($type): CI_Loader
	{
		return $this->templates->render([
			'title' => ucwords(str_replace('_', ' ', $type)),
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Website', ucwords(explode("_", $type)[1]), ucwords(str_replace('_', ' ', $type))
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'type_kategori' => ucwords(str_replace('_', ' ', $type)),
			'modals' => [],
		]);
	}

	/**
	 * Keperluan DataTables server-side
	 *
	 * @return CI_Output
	 */
	public function data(): CI_Output
	{
		method('get');
		//=========================================================//

		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.nama, a.type, a.created_at FROM kategori AS a
            WHERE a.is_active = '1' AND a.type = '"
				. strtolower(explode(" ", $this->input->get('type'))[1]) . "'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		return $this->output->set_content_type('application/json')
			->set_output($datatables->generate());
	}

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nama', 'nama', 'required|trim');

		if (!$this->form_validation->run()) {
			$this->output->set_content_type('application/json')
				->set_status_header(422);
			echo json_encode([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array()
			]);
			exit;
		}
	}

	/**
	 * Keperluan CRUD tambah data
	 *
	 * @return CI_Output
	 */
	public function insert(): CI_Output
	{
		has_permission('create-kategori');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_Kategori->insert(
			[
				'nama' => $this->input->post('nama', true),
				'slug' => slugify($this->input->post('nama', true)),
				'type' => strtolower(explode(" ", $this->input->post('type'))[1]),
				'is_active' => '1',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => get_user_id(),
			]
		);

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Failed',
					'errors' => $this->db->error()
				]));
		}

		$this->db->trans_commit();  // Commit transaction
		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Created successfuly'
			]));
	}

	/**
	 * Keperluan CRUD get where data
	 *
	 * @return CI_Output
	 */
	public function get_where(): CI_Output
	{
		method('get');
		//=========================================================//

		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Found',
				'data' => $this->M_Kategori->get_where(
					[
						'a.id' => $this->input->post('id', true),
						'a.is_active' => '1',
						'type' => strtolower(explode(" ", $this->input->post('type'))[1]),
					]
				)
			]));
	}

	/**
	 * Keperluan CRUD update data
	 *
	 * @return CI_Output
	 */
	public function update(): CI_Output
	{
		has_permission('update-kategori');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_Kategori->update(
			[
				'nama' => $this->input->post('nama', true),
				'slug' => slugify($this->input->post('nama', true)),
				'type' => strtolower(explode(" ", $this->input->post('type'))[1]),
				'is_active' => '1',
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => get_user_id(),
			],
			$this->input->post('id', true)
		);

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Failed',
					'errors' => $this->db->error()
				]));
		}
		$this->db->trans_commit();  // Commit transaction

		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Updated successfuly'
			]));
	}

	/**
	 * Keperluan CRUD delete data
	 *
	 * @return CI_Output
	 */
	public function delete(): CI_Output
	{
		has_permission('delete-kategori');
		method('post');
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_Kategori->update(
			[
				'is_active' => '0',
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => get_user_id()
			],
			$this->input->post('id', true)
		);

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Failed',
					'errors' => $this->db->error()
				]));
		}
		$this->db->trans_commit();  // Commit transaction

		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Deleted successfuly',
			]));
	}
}

/* End of file Kategori.php */
