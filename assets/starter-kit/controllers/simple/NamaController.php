<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class NamaController extends MY_Controller
{
	private $_path = 'backend/apa/'; // Contoh 'backend/dashboard/ / 'frontend/home/'

	/**
	 * NamaController constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Salah satu saja, role atau permission
		role("apa");    // admin, ...
		has_permission('access-apa');
		//=========================================================//

		$this->load->model($this->_path . 'M_NamaModel');   // Load model
	}

	/**
	 * Halaman index
	 *
	 * @return CI_Loader
	 */
	public function index(): CI_Loader
	{
		method('get');
		//=========================================================//        

		return $this->templates->render([
			'title' => 'Judul',
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', 'Apa'
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [
				'contents/' . $this->_path . 'modal/tambah',
				'contents/' . $this->_path . 'modal/ubah',
				'contents/' . $this->_path . 'modal/import',
			],
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
			"SELECT * FROM nama_tabel AS a
            WHERE a.is_active = '1'"
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
		$this->form_validation->set_rules('kolom_1', 'kolom_1', 'required|trim');
		$this->form_validation->set_rules('kolom_2', 'kolom_2', 'required|trim');

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
		has_permission('create-apa');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_NamaModel->insert(
			[
				'kolom_1' => $this->input->post('kolom_1', true),
				'kolom_2' => $this->input->post('kolom_2', true),
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
				'data' => $this->M_NamaModel->get_where(
					[
						'a.id' => $this->input->post('id', true),
						'a.is_active' => '1'
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
		has_permission('update-apa');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_NamaModel->update(
			[
				'kolom_1' => $this->input->post('kolom_1', true),
				'kolom_2' => $this->input->post('kolom_2', true),
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
		has_permission('delete-apa');
		method('post');
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_NamaModel->update(
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

/* End of file NamaController.php */
