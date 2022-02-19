<?php

class Roles extends MY_Controller
{
	/**
	 * Roles constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'roles';
		$this->_path = "auth/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model

		$this->lang->load('auth');
		has_permission("access-{$this->_name}");
	}

	/**
	 * Halaman index
	 *
	 */
	public function index()
	{
		method('get');

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'breadcrumb' => [
				'Auth', 'Manajemen', ucwords($this->_name)
			],
			'uri_segment' => $this->_path,
			'page' => $this->_path . 'index',
			'script' => $this->_path . 'js/script.js.php',
			'style' => $this->_path . 'css/style.css.php',
			'modals' => [],
		];

		render($config);
	}

	/**
	 * Keperluan DataTables server-side
	 *
	 */
	public function data_role()
	{
		method('post');
		// ================================================ //

		response($this->Datatable->list());
	}

	/**
	 * Create a new group
	 */
	public function create_role()
	{
		has_permission("create-{$this->_name}");
		if ($this->input->method() === 'get') :
			// display the create group form
			// set the flash data error message if there is one

			$this->data['group_name'] = [
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			];
			$this->data['description'] = [
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			];

			$config = [
				'title' => 'Create Role',
				'type' => 'backend',
				'breadcrumb' => [
					'Auth', 'Manajemen', ucwords($this->_name), 'Create'
				],
				'group_name' => $this->data['group_name'],
				'description' => $this->data['description'],
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'create/index',
				'script' => $this->_path . 'create/js/script.js.php',
				'style' => $this->_path . 'create/css/style.css.php',
				'modals' => []
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$new_group_id = $this->ion_auth->create_group(post('group_name'), post('description'));
			if ($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				response([
					'status' => true,
					'message' => 'Role berhasil ditambahkan',
					'data' => null
				], 200);
			} else {

				response([
					'status' => false,
					'message' => 'Gagal',
					'data' => null,
					'errors' => $this->ion_auth->errors()
				], 400);
			}

		endif;
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $uuid
	 */
	public function edit_role($uuid)
	{
		has_permission("update-{$this->_name}");
		$id = @$this->db->get_where('roles', [
			'uuid' => $uuid
		])->row()->id;

		// validate form input
		if ($this->input->method() === 'get') :

			$this->data['title'] = 'Edit Role';

			// bail if no group id given
			$group = $this->ion_auth->group($id)->row();

			if (!$group || !$id) show_404();

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			// pass the user to the view
			$this->data['group'] = $group;

			$this->data['group_name'] = [
				'name'    => 'group_name',
				'id'      => 'group_name',
				'type'    => 'text',
				'value'   => $this->form_validation->set_value('group_name', $group->name),
			];

			if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
				$this->data['group_name']['readonly'] = 'readonly';
			}

			$this->data['group_description'] = [
				'name'  => 'group_description',
				'id'    => 'group_description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_description', $group->description),
			];

			$config = [
				'title' => $this->data['title'],
				'type' => 'backend',
				'breadcrumb' => [
					'Auth', 'Manajemen', ucwords($this->_name), 'Edit'
				],
				'message' => $this->data['message'],
				'group' => $this->data['group'],
				'group_name' => $this->data['group_name'],
				'group_description' => $this->data['group_description'],
				'group_id' => $id,
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'edit/index',
				'script' => $this->_path . 'edit/js/script.js.php',
				'style' => $this->_path . 'edit/css/style.css.php',
				'modals' => []
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$group_update = $this->ion_auth->update_group($id, post('group_name'), array(
				'description' => post('group_description'),
				'uuid' => uuid(),
				'updated_at' => now()
			));

			if ($group_update) {
				response([
					'status' => true,
					'message' => 'Role berhasil diupdate',
					'data' => null,
				], 200);
			} else {
				response([
					'status' => false,
					'message' => 'Gagal',
					'data' => null,
					'errors' => $this->ion_auth->errors()
				], 400);
			}

		endif;
	}

	public function delete_role()
	{
		has_permission("delete-{$this->_name}");
		method('post');

		$id = @$this->db->get_where('roles', [
			'uuid' => post('uuid')
		])->row()->id;

		if (!$id) show_404();

		if ($this->ion_auth->delete_group($id)) {
			response([
				'status' => true,
				'message' => 'Role berhasil dihapus',
				'data' => null
			], 200);
		}

		response([
			'status' => false,
			'message' => 'Gagal',
			'data' => null,
			'errors' => $this->ion_auth->errors()
		], 400);
	}
}
