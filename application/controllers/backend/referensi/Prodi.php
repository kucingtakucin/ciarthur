<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Prodi extends MY_Controller
{
	/**
	 * Prodi constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'prodi';
		$this->_path = "backend/referensi/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		// Salah satu saja, role atau permission
		has_permission('access-prodi');
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
				'Backend', 'Referensi', ucwords($this->_name)
			],
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
	//=========================== AJAX ============================//
	//=============================================================//

	/**
	 * Keperluan AJAX Select2
	 *
	 */
	public function get_fakultas()
	{
		method('get');

		response([
			'status' => true,
			'data' => $this->db->like('nama', get('search'))
				->get_where('fakultas', ['is_active' => '1'])->result(),
			'last_query' => $this->db->last_query()
		]);
	}

	//=============================================================//
	//======================== VALIDATOR =========================//
	//=============================================================//

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator($status = null)
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($status === 'inline') $this->form_validation->set_rules('value', post('name'), 'required|trim');
		else {
			$this->form_validation->set_rules('nama', 'nama prodi', 'required|trim');
			$this->form_validation->set_rules('fakultas_id', 'fakultas', 'required|trim');
		}
		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array(),
				'last_query' => $this->db->last_query(),
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
				'fakultas_id' => post('fakultas_id'),
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		if (!$insert || !$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Created successfuly',
			'last_query' => $this->db->last_query(),
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
					'a.id' => $this->encryption->decrypt(base64_decode(post('id'))),
					'a.is_active' => '1'
				]
			),
			'last_query' => $this->db->last_query()
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

		$this->db->trans_begin();		// Begin transaction

		$update = $this->Crud->update(
			[
				'uuid' => uuid(),
				'nama' => post('nama'),
				'fakultas_id' => post('fakultas_id'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'id' => $this->encryption->decrypt(base64_decode(post('id')))
			]
		);

		if (!$update || !$this->db->trans_status()) {    // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly',
			'last_query' => $this->db->last_query(),
		]);
	}

	/**
	 * Keperluan CRUD hapus data
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
				'id' => $this->encryption->decrypt(base64_decode(post('id')))
			]
		);

		if (!$delete || !$this->db->trans_status()) {   // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
			'last_query' => $this->db->last_query(),
		]);
	}

	/**
	 * Keperluan CRUD ubah data inline
	 *
	 */
	public function inline()
	{
		has_permission("update-{$this->_name}");
		method('post');
		$this->_validator('inline');
		//=========================================================//

		$this->db->trans_begin();		// Begin transaction

		$update = $this->Crud->update(
			[
				'uuid' => uuid(),
				post('name') => post('value'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'id' => $this->encryption->decrypt(base64_decode(post('id')))
			]
		);

		if (!$update || !$this->db->trans_status()) {    // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly',
			'last_query' => $this->db->last_query(),
		]);
	}
}

/* End of file Prodi.php */
