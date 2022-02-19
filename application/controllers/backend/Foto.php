<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Foto extends MY_Controller
{
	/**
	 * Foto constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'foto';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		has_permission("access-{$this->_name}");
		//=========================================================//

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
		$this->load->model($this->_path . 'Crud');   // Load CRUD model
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
		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|trim');
		// $this->form_validation->set_rules('gambar', 'gambar', 'required|trim');

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
		$gambar = upload('gambar', "./uploads/foto/");

		$this->db->trans_begin();   // Begin transaction

		$galeri_id = @$this->db->get_where('galeri', [
			'uuid' => post('uuid_galeri'),
			'is_active' => '1',
			'deleted_at' => null
		])->row()->id;

		$this->Crud->insert(
			[
				'uuid' => uuid(),
				'judul' => post('judul'),
				'deskripsi' => post('deskripsi'),
				'galeri_id' => $galeri_id,
				'gambar' => $gambar,
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

		if (@$_FILES['gambar']['name'] && @$_FILES['gambar']['error'] !== 4) {
			if (file_exists("./uploads/foto/" . post('old_gambar'))) {
				unlink("./uploads/foto/" . post('old_gambar'));
			}

			$gambar = upload('gambar', "./uploads/foto/");
		} else $gambar = post('old_gambar');

		$this->db->trans_begin();   // Begin transaction

		$this->Crud->update(
			[
				'uuid' => uuid(),
				'judul' => post('judul'),
				'deskripsi' => post('deskripsi'),
				'gambar' => $gambar,
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
	 * Activate foto
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
	 * Deactivate foto
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

/* End of file Foto.php */
