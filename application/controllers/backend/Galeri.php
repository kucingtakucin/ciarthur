<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Galeri extends MY_Controller
{
	/**
	 * Galeri constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'galeri';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		$this->_path_foto = 'backend/foto/';
		//=========================================================//

		has_permission("access-{$this->_name}");
		//=========================================================//

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
		$this->load->model($this->_path . 'Crud');   // Load CRUD model
	}

	/**
	 * galeri index
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
			'uri_segment_foto' => $this->_path_foto,
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
		$this->form_validation->set_data(post());
		$this->form_validation->set_rules('judul', 'judul', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array()
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

		$this->db->trans_begin();   // Begin transaction

		$this->Crud->insert(
			[
				'uuid' => uuid(),
				'judul' => post('judul'),
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
	public function detail()
	{
		method('get');
		//=========================================================//

		response([
			'status' => true,
			'message' => 'Found',
			'data' => $this->Crud->detail(
				[
					'a.uuid' => post('uuid'),
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
				'judul' => post('judul'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'uuid' => post('uuid')
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
		has_permission("delete-{$this->_name}");
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
				'uuid' => post('uuid')
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

	/**
	 * Activate galeri
	 *
	 */
	public function activate()
	{
		method('post');

		$this->db->trans_start();

		$this->Crud->update([
			'is_published' => '1'
		], [
			'uuid' => post('uuid'),
			'is_active' => '1',
			'deleted_at' => null
		]);

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {	// Check transaction status
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'payload' => post()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Status berhasil diubah',
			'query' => $this->db->last_query(),
			'payload' => post()
		], 200);
	}

	/**
	 * Deactivate galeri
	 *
	 */
	public function deactivate()
	{
		method('post');

		$this->db->trans_start();

		$this->Crud->update([
			'is_published' => '0'
		], [
			'uuid' => post('uuid'),
			'is_active' => '1',
			'deleted_at' => null
		]);

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {	// Check transaction status
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'payload' => post()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Status berhasil diubah',
			'query' => $this->db->last_query(),
			'payload' => post()
		], 200);
	}
}

/* End of file Galeri.php */
