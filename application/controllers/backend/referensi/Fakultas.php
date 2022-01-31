<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fakultas extends MY_Controller
{
	/**
	 * Fakultas constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'fakultas';
		$this->_path = "backend/referensi/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		// Salah satu saja, role atau permission
		has_permission("access-{$this->_name}");
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
			'breadcrumb' => [
				'Backend', 'Referensi', ucwords($this->_name)
			],
			'uri_segment' => $this->_path,
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [],
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
		method('post');
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
		$this->form_validation->set_rules('nama', 'nama fakultas', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array(),
				'query' => $this->db->last_query(),
				'csrf' => csrf()
			], 422);
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

		$this->db->trans_begin();		// Begin transaction

		$insert = $this->Crud->insert(
			[
				'uuid' => uuid(),
				'nama' => post('nama'),
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		//=========================================================//
		if (!$insert || !$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'csrf' => csrf()
			], 404);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Created successfuly',
			'query' => $this->db->last_query(),
			'csrf' => csrf()
		]);
	}

	/**
	 * Keperluan CRUD get where data
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
					'a.id' => $this->encryption->decrypt(base64_decode(post('id', true))),
					'a.is_active' => '1'
				]
			),
			'query' => $this->db->last_query(),
		]);
	}

	/**
	 * Keperluan CRUD update data
	 *
	 */
	public function update()
	{
		has_permission("update-{$this->_name}");
		method('post');
		$this->_validator();
		//=========================================================//

		$this->db->trans_begin();		// Begin transaction

		$update = $this->Crud->update(
			[
				'uuid' => uuid(),
				'nama' => post('nama'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'id' => $this->encryption->decrypt(base64_decode(post('id', true)))
			]
		);

		if (!$update || !$this->db->trans_status()) {    // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'csrf' => csrf()
			], 404);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly',
			'query' => $this->db->last_query(),
			'csrf' => csrf()
		]);
	}

	/**
	 * Keperluan CRUD delete data
	 *
	 */
	public function delete()
	{
		has_permission("delete-{$this->_name}");
		method('post');
		//=========================================================//

		$this->db->trans_begin();		// Begin transaction

		$delete = $this->Crud->update(
			[
				'is_active' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			],
			[
				'id' => $this->encryption->decrypt(urldecode(post('id')))
			]
		);

		if (!$delete || !$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'csrf' => csrf()
			], 404);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
			'query' => $this->db->last_query(),
			'csrf' => csrf()
		]);
	}
}

/* End of file Fakultas.php */
