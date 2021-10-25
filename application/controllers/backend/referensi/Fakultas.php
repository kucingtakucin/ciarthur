<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Fakultas extends MY_Controller
{
	private $_path = 'backend/referensi/fakultas/'; // Contoh 'backend/dashboard/ / 'frontend/home/'

	/**
	 * NamaController constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Salah satu saja, role atau permission
		has_permission('access-fakultas');
		//=========================================================//


		$this->load->model($this->_path . 'M_Fakultas');   // Load model
		$this->load->library(['upload', 'form_validation']);  // Load library upload
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
			'title' => 'Fakultas',
			'type' => 'backend', // auth, frontend, backend
			'breadcrumb' => [
				'Backend', 'Referensi', 'Fakultas'
			],
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
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
			"SELECT a.id, a.nama, a.created_at FROM fakultas AS a
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
		$this->form_validation->set_rules('nama', 'nama fakultas', 'required|trim');
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
		has_permission('create-fakultas');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$insert = $this->M_Fakultas->insert(
			[
				'nama' => $this->input->post('nama', true),
				'is_active' => '1',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => get_user_id(),
			]
		);

		if (!$this->db->trans_status() || !$insert) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
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
				'data' => $this->M_Fakultas->get_where(
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
		has_permission('update-fakultas');
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$update = $this->M_Fakultas->update(
			[
				'nama' => $this->input->post('nama', true),
				'is_active' => '1',
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => get_user_id(),
			],
			$this->input->post('id', true)
		);

		if (!$this->db->trans_status() || !$update) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
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
		has_permission('delete-fakultas');
		method('post');
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$delete = $this->M_Fakultas->update(
			[
				'is_active' => '0',
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => get_user_id()
			],
			$this->input->post('id', true)
		);

		if (!$this->db->trans_status() || !$delete) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
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
