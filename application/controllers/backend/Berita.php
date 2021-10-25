<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends MY_Controller
{
	private $_path = 'backend/berita/'; // Contoh 'backend/dashboard/ / 'frontend/home/'
	private $_path_kategori = 'backend/referensi/kategori/';

	/**
	 * Berita constructor
	 */
	public function __construct()
	{
		parent::__construct();
		has_permission('access-berita');
		//=========================================================//

		$this->load->model($this->_path . 'M_Berita');   // Load model
		$this->load->model($this->_path_kategori . 'M_Kategori');   // Load model
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

		return $this->templates->render([
			'title' => 'Berita',
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Website', 'Berita'
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [],
		]);
	}

	public function create()
	{
		method('get');
		//=========================================================//        

		return $this->templates->render([
			'title' => 'Berita',
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Website', 'Berita', 'Create'
			],
			'page' => 'contents/' . $this->_path . 'create',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [],
		]);
	}

	public function edit($id)
	{
		method('get');
		//=========================================================//        

		return $this->templates->render([
			'title' => 'Berita',
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Website', 'Berita', 'Edit'
			],
			'page' => 'contents/' . $this->_path . 'edit',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'id' => $id,
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
			"SELECT a.id, a.judul, a.gambar, a.slug, a.konten,
			a.is_published, a.created_at, a.created_by,
			(SELECT b.nama FROM kategori AS b 
			WHERE b.id = a.kategori_id) AS nama_kategori 
			FROM berita AS a
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
		$this->form_validation->set_rules('judul', 'judul', 'required|trim');
		// $this->form_validation->set_rules('gambar', 'gambar', 'required|trim');
		$this->form_validation->set_rules('kategori_id', 'kategori', 'required');
		$this->form_validation->set_rules('tags[]', 'tags', 'required');
		$this->form_validation->set_rules('konten', 'konten', 'required|trim');
		// $this->form_validation->set_rules('is_published', 'is_published', 'required');

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

	private function _validator_kategori()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nama', 'nama', 'required|trim');

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
		has_permission('create-berita');
		method('post');
		$this->_validator();
		//=========================================================//

		$config['upload_path'] = './uploads/berita/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload("gambar")) {
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode([
					'status' => false,
					'message' => $this->upload->display_errors('', '')
				]));
		}

		$this->db->trans_begin();   // Begin transaction
		$berita_id = $this->M_Berita->insert(
			[
				'judul' => $this->input->post('judul', true),
				'gambar' => $this->upload->data('file_name'),
				'slug' => $this->input->post('slug', true),
				'konten' => $this->input->post('konten', true),
				'kategori_id' => $this->input->post('kategori_id', true),
				'is_published' => '0',
				'is_active' => '1',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => get_user_id(),
			]
		);

		# Check transaction status for berita
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

		foreach ($this->input->post('tags') as $tag) {
			$slug = $this->db->get_where('tags', [
				'slug' => slugify(strtolower($tag))
			]);

			if (!$slug) {
				$this->db->trans_begin();   // Begin transaction
				$this->db->insert('tags', [
					'nama' => $tag,
					'slug' => slugify(strtolower($tag))
				]);
				$this->db->trans_commit();  // Commit transaction
				$tag_id = $this->db->insert_id();
			}

			$this->db->trans_begin();   // Begin transaction
			$this->db->insert('berita_tag', [
				'berita_id' => $berita_id,
				'tag_id' => $tag_id
			]);
			$this->db->trans_commit();  // Commit transaction
		}

		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Created successfuly'
			]));
	}

	public function insert_kategori(): CI_Output
	{
		has_permission('create-kategori');
		method('post');
		$this->_validator_kategori();
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_Kategori->insert(
			[
				'nama' => $this->input->post('nama', true),
				'slug' => slugify($this->input->post('nama', true)),
				'type' => 'berita',
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
				'data' => $this->M_Berita->get_where(
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
		has_permission('update-berita');
		method('post');
		$this->_validator();
		//=========================================================//

		$config['upload_path'] = './uploads/berita/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);
		if ($_FILES['foto']['error'] !== 4) {
			if (file_exists("./uploads/berita/{$this->input->post('old_foto')}")) {
				unlink("./uploads/berita/{$this->input->post('old_foto')}");
			}

			if (!$this->upload->do_upload("foto")) {
				return $this->output->set_content_type('application/json')
					->set_status_header(404)
					->set_output(json_encode([
						'status' => false,
						'message' => $this->upload->display_errors()
					]));
			}
		}

		$this->db->trans_begin();   // Begin transaction
		$this->M_Berita->update(
			[
				'judul' => $this->input->post('judul', true),
				'gambar' =>  $_FILES['foto']['error'] === 4
					? $this->input->post('old_gambar') : $this->upload->data('file_name'),
				'slug' => $this->input->post('slug', true),
				'konten' => $this->input->post('konten', true),
				'kategori_id' => $this->input->post('kategori_id', true),
				'is_published' => $this->input->post('is_published', true),
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
		has_permission('delete-berita');
		method('post');
		//=========================================================//

		$this->db->trans_begin();   // Begin transaction
		$this->M_Berita->update(
			[
				'is_active' => '0',
				'is_published' => '0',
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

	// =============================================================================== //
	// =============================================================================== //

	public function ajax_get_kategori()
	{
		method('get');
		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'data' => $this->db->like('nama', $this->input->get('search'))
					->get_where('kategori', ['type' => $this->input->get('type')])->result()
			]));
	}

	public function ajax_get_tags()
	{
		method('get');
		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'data' => $this->db->like('nama', $this->input->get('search'))
					->get('tags')->result()
			]));
	}
}

/* End of file Berita.php */
