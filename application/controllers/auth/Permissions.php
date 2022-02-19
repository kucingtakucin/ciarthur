<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

(defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Permission.php
 *
 * @package     CI-ACL
 * @author      Steve Goodwin
 * @copyright   2015 Plumps Creative Limited
 */

class Permissions extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'permissions';
		$this->_path = "auth/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

		has_permission("access-{$this->_name}");
	}

	public function index()
	{
		redirect($this->_path . 'manage');
	}

	public function manage()
	{
		method('get');

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'breadcrumb' => [
				'Auth', 'Manajemen', ucwords($this->_name), 'Manage'
			],
			'uri_segment' => $this->_path,
			'permissions' => $this->ion_auth_acl->permissions('full'),
			'page' => $this->_path . 'manage/index',
			'script' => $this->_path . 'manage/js/script.js.php',
			'style' => $this->_path . 'manage/css/style.css.php',
			'modals' => [
				$this->_path . 'manage/modal/tambah',
				$this->_path . 'manage/modal/ubah',
			],
		];

		render($config);
	}

	public function data_permission()
	{
		method('post');
		$this->load->model($this->_path . 'Datatable', 'Datatable_permissions');   // Load Datatable model
		response($this->Datatable_permissions->list());
	}

	public function data_roles()
	{
		method('post');
		$this->load->model('auth/roles/' . 'Datatable', 'Datatable_roles');   // Load Datatable model
		response($this->Datatable_roles->list());
	}

	public function add_permission()
	{
		has_permission("create-{$this->_name}");
		method('post');

		$new_permission_id = $this->ion_auth_acl->create_permission(post('perm_key'), post('perm_name'));
		if ($new_permission_id) {
			// check to see if we are creating the permission
			// redirect them back to the admin page
			// $this->session->set_flashdata('message', $this->ion_auth->messages());
			// redirect("/permission/permissions", 'refresh');
			response([
				'status' => true,
				'message' => 'Permission berhasil ditambahkan',
				'data' => null,
			], 200);
		}
		response([
			'status' => false,
			'message' => 'Gagal',
			'data' => null,
			'errors' => $this->ion_auth_acl->errors()
		], 404);
	}

	public function update_permission()
	{
		has_permission("update-{$this->_name}");
		method('post');

		$permission_id = $this->encryption->decrypt(base64_decode(post('id')));

		$additional_data    =   array(
			'perm_name' =>  post('perm_name')
		);

		$update_permission = $this->ion_auth_acl->update_permission($permission_id, post('perm_key'), $additional_data);
		if ($update_permission) {
			// check to see if we are creating the permission
			// redirect them back to the admin page
			response([
				'status' => true,
				'message' => 'Permission berhasil diubah',
				'data' => null
			], 200);
		}
		response([
			'status' => false,
			'message' => 'Failed',
			'data' => null,
			'errors' => $this->ion_auth_acl->errors()
		], 404);
	}

	public function delete_permission()
	{
		has_permission("delete-{$this->_name}");
		method('post');

		$permission_id = $this->encryption->decrypt(base64_decode(post('id')));

		if ($this->ion_auth_acl->remove_permission($permission_id)) {
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			response([
				'status' => true,
				'message' => 'Permission berhasil dihapus',
				'data' => null
			], 200);
		} else {
			response([
				'status' => true,
				'message' => 'Failed',
				'data' => null,
				'errors' => $this->ion_auth_acl->errors()
			], 404);
		}
	}

	public function role()
	{
		method('get');

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'breadcrumb' => [
				'Auth', 'Manajemen', ucwords($this->_name), 'Role'
			],
			'uri_segment' => $this->_path,
			'permissions' => $this->ion_auth_acl->permissions('full'),
			'page' => $this->_path . 'roles/index',
			'script' => $this->_path . 'roles/js/script.js.php',
			'style' => $this->_path . 'roles/css/style.css.php',
			'modals' => [],
		];

		render($config);
	}

	public function role_permissions($id)
	{
		$group_id =  (ctype_xdigit($id) && strlen($id) % 2 === 0) ? $this->encryption->decrypt(hex2bin($id)) : null;

		if (!$group_id) show_404();

		if ($this->input->method() === 'get') :

			$config = [
				'title' => 'Permissions',
				'type' => 'backend', // auth, frontend, backend
				'uri_segment' => $this->_path,
				'breadcrumb' => [
					'Auth', 'Manajemen', 'Permissions', 'Role Access'
				],
				'permissions' => $this->ion_auth_acl->permissions('full'),
				'group_id' => $group_id,
				'group_permissions' => $this->ion_auth_acl->get_group_permissions($group_id),
				'page' => $this->_path . 'roles/permissions/index',
				'script' => $this->_path . 'roles/permissions/js/script.js.php',
				'style' => $this->_path . 'roles/permissions/css/style.css.php',
				'modals' => [],

			];

			render($config);

		elseif ($this->input->method() === 'post') :

			foreach (post() as $k => $v) {
				if (substr($k, 0, 5) == 'perm_') {
					$permission_id = str_replace("perm_", "", $k);

					if ($v == "X")
						$this->ion_auth_acl->remove_permission_from_group($group_id, $permission_id);
					else
						$this->ion_auth_acl->add_permission_to_group($group_id, $permission_id, $v);
				}
			}

			response([
				'status' => true,
				'message' => 'Permission berhasil diatur',
				'data' => null
			], 200);
		endif;
	}
}
