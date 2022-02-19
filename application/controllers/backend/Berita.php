<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends MY_Controller
{
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

		$this->load->model($this->_path . 'Crud', 'Crud_berita');   // Load CRUD model
		$this->load->model($this->_path_kategori . 'Crud', 'Crud_kategori');   // Load Datatable model
		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
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
	private function _validator($status = 'tambah')
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data(post());

		if ($status === 'tambah' || $this->Crud_berita->num_rows([
			'judul' => post('judul'),
			'is_active' => '1',
			'uuid !=' => $status === 'ubah' ? post('uuid') : null
		])) $is_unique = '|is_unique[berita.judul]';
		else $is_unique = '';

		$this->form_validation->set_rules('judul', 'judul', 'required|trim' . $is_unique);
		$this->form_validation->set_rules('kategori_id', 'kategori', 'required');
		$this->form_validation->set_rules('tags[]', 'tags', 'required');
		$this->form_validation->set_rules('konten', 'konten', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array(),
				'payload' => post()
			], 422);
	}

	private function _validator_kategori()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data(post());
		$this->form_validation->set_rules('nama', 'nama', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array(),
				'query' => $this->db->last_query(),
				'payload' => post()
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

			$gambar = upload('gambar', "./uploads/berita/");

			$this->db->trans_start();	// Transaction start

			$berita_id = $this->Crud_berita->insert(
				[
					'uuid' => uuid(),
					'judul' => post('judul', true),
					'gambar' => $gambar,
					'slug' => slugify(post('judul', true)),
					'konten' => post('konten', true),
					'kategori_id' => post('kategori_id', true),
					'is_published' => '0',
					'is_active' => '1',
					'created_at' => now(),
					'created_by' => get_user_id(),
				]
			);
			$last_query = $this->db->last_query();

			if (post('tags[]')) {
				foreach (post('tags[]') as $tag) {
					$slug = $this->db->get_where('tags', [
						'slug' => slugify(strtolower($tag))
					])->row();

					if (!$slug) {
						$this->db->insert('tags', [
							'uuid' => uuid(),
							'nama' => $tag,
							'slug' => slugify(strtolower($tag)),
							'is_active' => '1',
							'created_at' => now(),
							'created_by' => get_user_id()
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
					'errors' => $this->db->error(),
					'query' => $last_query,
					'payload' => post()
				], 500);
			}

			response([
				'status' => true,
				'message' => 'Created successfuly',
				'query' => $this->db->last_query(),
				'payload' => post()
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
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'payload' => post()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Created successfuly',
			'query' => $this->db->last_query(),
			'payload' => post()
		]);
	}

	/**
	 * Keperluan CRUD get where data
	 *
	 */
	public function detail()
	{
		method('get');
		//=========================================================//

		response([
			'status' => true,
			'message' => 'Found',
			'data' => $this->Crud_berita->detail(
				[
					'a.id' => post('id', true),
					'a.is_active' => '1'
				]
			),
			'query' => $this->db->last_query(),
			'payload' => get()
		]);
	}

	/**
	 * Keperluan CRUD update data
	 *
	 */
	public function update($uuid = null)
	{
		has_permission("update-{$this->_name}");

		$data = $this->Crud_berita->detail(
			[
				'a.uuid' => $uuid,
				'a.is_active' => '1'
			]
		);

		if (!$data) show_404();

		if ($this->input->method() === 'get') :

			$tags = $this->db->select('a.tag_id,
				(SELECT b.nama FROM tags b
				WHERE b.id = a.tag_id) tag_name
			')
				->from('berita_tag a')
				->where([
					'berita_id' => @$data->id
				])
				->get()->result();

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
				'data' => $data,
				'tags' => $tags,
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$this->_validator('ubah');
			//=========================================================//
			if (@$_FILES['gambar']['name'] && @$_FILES['gambar']['error'] !== 4) {
				if (file_exists("./uploads/berita/" . @$data->gambar)) {
					unlink("./uploads/berita/" . @$data->gambar);
				}

				$gambar = upload('gambar', "./uploads/berita/");
			} else $gambar = @$data->gambar;

			$this->db->trans_start();

			$this->Crud_berita->update(
				[
					'uuid' => uuid(),
					'judul' => post('judul', true),
					'gambar' =>  $gambar,
					'slug' => post('slug', true),
					'konten' => post('konten', true),
					'kategori_id' => post('kategori_id', true),
					'is_active' => '1',
					'updated_at' => now(),
					'updated_by' => get_user_id(),
				],
				[
					'id' => @$data->id
				]
			);
			$last_query = $this->db->last_query();

			if (post('tags[]')) {

				$this->db->delete('berita_tag', [
					'berita_id' => @$data->id
				]);

				foreach (post('tags[]') as $tag) {
					$slug = $this->db->get_where('tags', [
						'slug' => slugify(strtolower($tag))
					])->row();

					$id = $this->db->get_where('tags', [
						'id' => $tag
					])->row();

					if (!$slug && !$id) {
						$this->db->insert('tags', [
							'uuid' => uuid(),
							'nama' => $tag,
							'slug' => slugify(strtolower($tag))
						]);
						$tag_id = $this->db->insert_id();
					}

					$this->db->insert('berita_tag', [
						'berita_id' => @$data->id,
						'tag_id' => $tag_id ?? $tag
					]);
				}
			}

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
				'message' => 'Updated successfuly',
				'query' => $last_query,
				'payload' => post()
			]);
		endif;
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

		$this->db->trans_start();

		$this->Crud_berita->update(
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
				'errors' => $this->db->error(),
				'query' => $this->db->last_query(),
				'payload' => post()
			], 500);
		}

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
			'query' => $this->db->last_query(),
			'payload' => post()
		]);
	}

	/**
	 * Activate berita
	 *
	 */
	public function activate()
	{
		method('post');

		$this->db->trans_start();

		$this->Crud_berita->update([
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
	 * Deactivate berita
	 *
	 */
	public function deactivate()
	{
		method('post');

		$this->db->trans_start();

		$this->Crud_berita->update([
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
