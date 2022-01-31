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

		role('admin');
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
		method('get');

		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.perm_key, a.perm_name, a.created_at
			FROM `permissions` AS a"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		$datatables->add('encrypt_id', function ($data) {
			return urlencode($this->encryption->encrypt($data['id']));
		});

		response($datatables->generate()->toArray());
	}

	public function data_roles()
	{
		method('get');

		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.name, a.description, a.created_at
			FROM roles AS a WHERE a.is_active = '1'"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		$datatables->add('encrypt_id', function ($data) {
			return urlencode($this->encryption->encrypt($data['id']));
		});

		response($datatables->generate()->toArray());
	}

	public function add_permission()
	{
		method('post');

		$new_permission_id = $this->ion_auth_acl->create_permission($this->input->post('perm_key'), $this->input->post('perm_name'));
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
		method('post');

		$permission_id = $this->encryption->decrypt(urldecode($this->input->post('id')));

		$additional_data    =   array(
			'perm_name' =>  $this->input->post('perm_name')
		);

		$update_permission = $this->ion_auth_acl->update_permission($permission_id, $this->input->post('perm_key'), $additional_data);
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
		method('post');

		$permission_id = $this->encryption->decrypt(urldecode($this->input->post('id')));

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
		$group_id = $this->encryption->decrypt(urldecode($id));

		if (!$group_id) redirect($this->_path . 'role');

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
