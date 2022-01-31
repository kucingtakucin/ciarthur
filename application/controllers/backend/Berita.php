<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
use Ramsey\Uuid\Uuid;

defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends MY_Controller
{
	private $_path_kategori = 'backend/referensi/kategori/';

	/**
	 * Berita constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'berita';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		$this->_path_kategori = 'backend/referensi/kategori/';

		has_permission("access-{$this->_name}");
		//=========================================================//

		$this->load->model($this->_path . 'Crud');   // Load model
		$this->load->model($this->_path_kategori . 'Crud_kategori');   // Load model
	}

	/**
	 * Halaman index
	 *
	 */
	public function index()
	{
		redirect($this->_path . 'manage');
	}

	public function manage()
	{
		method('get');
		//=========================================================//        
		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Website', ucwords($this->_name)
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
	private function _validator($status = 'tambah')
	{
		$this->form_validation->set_error_delimiters('', '');

		if ($status === 'tambah' || $this->Crud->num_rows([
			'judul' => post('judul'),
			'is_active' => '1',
			'id != ' => $status === 'ubah' ? post('id') : 'null'
		])) {
			$is_unique = '|is_unique[berita.judul]';
		} else {
			$is_unique = '';
		}

		$this->form_validation->set_rules('judul', 'judul', 'required|trim' . $is_unique);
		$this->form_validation->set_rules('kategori_id', 'kategori', 'required');
		$this->form_validation->set_rules('tags[]', 'tags', 'required');
		$this->form_validation->set_rules('konten', 'konten', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array()
			], 422);
	}

	private function _validator_kategori()
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

		if ($this->input->method() === 'get') :

			$config = [
				'title' => ucwords($this->_name),
				'type' => 'backend', // auth, frontend, backend
				'uri_segment' => $this->_path,
				'breadcrumb' => [
					'Backend', 'Website', ucwords($this->_name), 'Create'
				],
				'page' => 'contents/' . $this->_path . 'create/index',
				'script' => 'contents/' . $this->_path . 'create/js/script.js.php',
				'style' => 'contents/' . $this->_path . 'create/css/style.css.php',
				'modals' => [],
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$this->_validator();

			$config['upload_path'] = './uploads/berita/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 2048;
			$config['encrypt_name'] = true;
			$config['remove_spaces'] = true;

			$this->upload->initialize($config);

			if (!$this->upload->do_upload("gambar")) {
				response([
					'status' => false,
					'message' => $this->upload->display_errors('', '')
				], 404);
			}

			$this->db->trans_start();	// Transaction start

			$berita_id = $this->Crud->insert(
				[
					'uuid' => uuid(),
					'judul' => post('judul', true),
					'gambar' => $this->upload->data('file_name'),
					'slug' => slugify(post('judul', true)),
					'konten' => post('konten', true),
					'kategori_id' => post('kategori_id', true),
					'is_published' => '0',
					'is_active' => '1',
					'created_at' => now(),
					'created_by' => get_user_id(),
				]
			);

			if (post('tags[]')) {
				foreach (post('tags[]') as $tag) {
					$slug = $this->db->get_where('tags', [
						'slug' => slugify(strtolower($tag))
					])->row();

					if (!$slug) {
						$this->db->insert('tags', [
							'uuid' => uuid(),
							'nama' => $tag,
							'slug' => slugify(strtolower($tag))
						]);
						$tag_id = $this->db->insert_id();
					}

					$this->db->insert('berita_tag', [
						'berita_id' => $berita_id,
						'tag_id' => $tag_id
					]);
				}
			}

			$this->db->trans_complete();	// Transaction complete

			if (!$this->db->trans_status()) {	// Check transaction status
				response([
					'status' => false,
					'message' => 'Failed',
					'errors' => $this->db->error()
				], 500);
			}

			response([
				'status' => true,
				'message' => 'Created successfuly'
			], 200);
		endif;
	}

	public function insert_kategori()
	{
		has_permission('create-kategori');
		method('post');
		//=========================================================//
		$this->_validator_kategori();

		$this->db->trans_start();

		$this->Crud_kategori->insert(
			[
				'uuid' => uuid(),
				'nama' => post('nama', true),
				'slug' => slugify(post('nama', true)),
				'type' => 'berita',
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {	// Check transaction status
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Created successfuly'
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
					'a.id' => post('id', true),
					'a.is_active' => '1'
				]
			)
		]);
	}

	/**
	 * Keperluan CRUD update data
	 *
	 */
	public function update($uuid = null)
	{
		has_permission("update-{$this->_name}");

		if ($this->input->method() === 'get') :

			if (!$uuid) redirect($this->_path, 'refresh');

			$config = [
				'title' => 'Berita',
				'type' => 'backend', // auth, frontend, backend
				'uri_segment' => $this->_path,
				'breadcrumb' => [
					'Backend', 'Website', 'Berita', 'Edit'
				],
				'page' => 'contents/' . $this->_path . 'edit/index',
				'script' => 'contents/' . $this->_path . 'edit/js/script.js.php',
				'style' => 'contents/' . $this->_path . 'edit/css/style.css.php',
				'uuid' => $uuid,
				'modals' => [],
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$this->_validator();
			//=========================================================//

			$config['upload_path'] = './uploads/berita/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 2048;
			$config['encrypt_name'] = true;
			$config['remove_spaces'] = true;
			$this->upload->initialize($config);

			if ($_FILES['foto']['error'] !== 4) {
				if (file_exists("./uploads/berita/" . post('old_foto'))) {
					unlink("./uploads/berita/" . post('old_foto'));
				}

				if (!$this->upload->do_upload("foto")) {
					response([
						'status' => false,
						'message' => $this->upload->display_errors()
					], 400);
				}
			}

			$this->db->trans_start();

			$this->Crud->update(
				[
					'uuid' => uuid(),
					'judul' => post('judul', true),
					'gambar' =>  $_FILES['foto']['error'] === 4
						? post('old_gambar') : $this->upload->data('file_name'),
					'slug' => post('slug', true),
					'konten' => post('konten', true),
					'kategori_id' => post('kategori_id', true),
					'is_published' => post('is_published', true),
					'is_active' => '1',
					'updated_at' => now(),
					'updated_by' => get_user_id(),
				],
				[
					'uuid' => post('uuid', true)
				]
			);

			$this->db->trans_complete();

			if (!$this->db->trans_status()) {	// Check transaction status
				response([
					'status' => false,
					'message' => 'Failed',
					'errors' => $this->db->error()
				], 500);
			}

			response([
				'status' => true,
				'message' => 'Updated successfuly'
			]);
		endif;
	}

	/**
	 * Keperluan CRUD delete data
	 *
	 */
	public function delete()
	{
		has_permission('delete-berita');
		method('post');
		//=========================================================//

		$this->db->trans_start();

		$this->Crud->update(
			[
				'is_active' => '0',
				'is_published' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			],
			[
				'uuid' => post('uuid')
			]
		);

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {	// Check transaction status
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
		]);
	}

	//=============================================================//
	//========================== AJAX =============================//
	//=============================================================//

	public function ajax_get_kategori()
	{
		method('get');
		//=========================================================//

		response([
			'status' => true,
			'data' => $this->db->like('nama', get('search'))
				->get_where('kategori', ['type' => get('type')])->result()
		]);
	}

	public function ajax_get_tags()
	{
		method('get');
		//=========================================================//

		response([
			'status' => true,
			'data' => $this->db->like('nama', get('search'))
				->get('tags')->result()
		]);
	}
}

/* End of file Berita.php */
