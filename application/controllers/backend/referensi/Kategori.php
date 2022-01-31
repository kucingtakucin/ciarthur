<?php

use Ramsey\Uuid\Uuid;

defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends MY_Controller
{
	/**
	 * Kategori constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'kategori';
		$this->_path = "backend/referensi/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		has_permission('access-kategori');
		//=========================================================//

		$this->load->model($this->_path . 'Crud');   // Load CRUD model
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

	public function manage($type)
	{
		$config = [
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

		response($this->Crud->datatables());
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
		$this->form_validation->set_rules('nama', 'nama', 'required|trim');

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
				'uuid' => Uuid::uuid4()->toString(),
				'nama' => post('nama'),
				'slug' => slugify(post('nama')),
				'type' => strtolower(explode(" ", post('type'))[1]),
				'is_active' => '1',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => get_user_id(),
			]
		);

		if (!$this->db->trans_status()) {  // Check transaction status
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
					'a.is_active' => '1',
					'type' => strtolower(explode(" ", post('type'))[1]),
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
				'uuid' => Uuid::uuid4()->toString(),
				'nama' => post('nama'),
				'slug' => slugify(post('nama')),
				'type' => strtolower(explode(" ", post('type'))[1]),
				'is_active' => '1',
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => get_user_id(),
			],
			[
				'id' => post('id')
			]
		);

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
		], 200);
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
				'uuid' => Uuid::uuid4()->toString(),
				'is_active' => '0',
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => get_user_id()
			],
			[
				'id' => post('id')
			]
		);

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

/* End of file Kategori.php */
