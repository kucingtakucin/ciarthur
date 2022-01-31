<?php

defined('BASEPATH') or exit('No direct script access allowed');

class NamaController extends MY_Controller
{
	/**
	 * NamaController constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'apa';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		// Salah satu saja, role atau permission
		role("apa");    // admin, ...
		has_permission('access-apa');
		//=========================================================//

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
		$this->load->model($this->_path . 'Crud');   // Load CRUD model
	}

	/**
	 * Halaman index
	 *
	 */
	public function index()
	{
		method('get');
		//=========================================================//        

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', ucwords($this->_name)
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [
				// 'contents/' . $this->_path . 'modal/tambah',
				// 'contents/' . $this->_path . 'modal/ubah',
			],
		];

		render($config);
	}

	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	/**
	 * Keperluan DataTables server-side
	 *
	 */
	public function data()
	{
		method('get');
		//=========================================================//

		response($this->Datatable->list());
	}

	//=============================================================//
	//======================== VALIDATOR =========================//
	//=============================================================//

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kolom_1', 'kolom_1', 'required|trim');
		$this->form_validation->set_rules('kolom_2', 'kolom_2', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array()
			]);
	}

	//=============================================================//
	//=========================== CRUD ============================//
	//=============================================================//
	/**
	 * Keperluan CRUD tambah data
	 *
	 */

	public function insert()
	{
		has_permission("create-{$this->_name}");
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction

		$this->Crud->insert(
			[
				'uuid' => uuid(),
				'kolom_1' => post('kolom_1'),
				'kolom_2' => post('kolom_2'),
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		//=========================================================//

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();  // Commit transaction

		response([
			'status' => true,
			'message' => 'Created successfuly'
		]);
	}

	/**
	 * Keperluan CRUD detail data
	 *
	 */
	public function get_where()
	{
		method('get');
		//=========================================================//

		response([
			'status' => true,
			'message' => 'Found',
			'data' => $this->Crud->get_where(
				[
					'a.id' => post('id'),
					'a.is_active' => '1'
				]
			)
		]);
	}

	/**
	 * Keperluan CRUD ubah data
	 *
	 */
	public function update()
	{
		has_permission("update-{$this->_name}");
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction

		$this->Crud->update(
			[
				'uuid' => uuid(),
				'kolom_1' => post('kolom_1'),
				'kolom_2' => post('kolom_2'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'id' => post('id')
			]
		);

		//=========================================================//

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();  // Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly'
		]);
	}

	/**
	 * Keperluan CRUD hapus data
	 *
	 */
	public function delete()
	{
		has_permission('delete-apa');
		method('post');
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction

		$this->Crud->update(
			[
				'is_active' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			],
			[
				'id' => post('id')
			]
		);

		//=========================================================//

		if (!$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();    // Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();  // Commit transaction

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
		]);
	}
}

/* End of file NamaController.php */
