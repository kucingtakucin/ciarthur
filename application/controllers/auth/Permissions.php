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
    private $_path = 'auth/permissions/'; // Contoh 'backend/admin/dashboard'

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        role('admin');
    }

    public function index()
    {
        redirect($this->_path . 'manage');
    }

    public function manage()
    {
        // $this->load->view('auth/permission/manage');
        return $this->templates->render([
            'title' => 'Permissions',
            'type' => 'backend', // auth, frontend, backend
            'breadcrumb' => [
                'Auth', 'Manajemen', 'Permissions', 'Manage'
            ],
            'uri_segment' => $this->_path,
            'permissions' => $this->ion_auth_acl->permissions('full'),
            'page' => $this->_path . 'manage/index',
            'script' => $this->_path . 'manage/js/script_js',
            'style' => $this->_path . 'manage/css/style_css',
            'modals' => [
                $this->_path . 'manage/modal/tambah',
                $this->_path . 'manage/modal/ubah',
            ],
        ]);
    }

    public function data_permission()
    {
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT a.id, a.perm_key, a.perm_name, a.created_at
			FROM `permissions` AS a"
        );

        // Add row index
        $datatables->add('DT_RowIndex', function () {
            return 0;
        });

        return $this->output->set_content_type('application/json')
            ->set_output($datatables->generate());
    }

    public function data_roles()
    {
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT a.id, a.name, a.description, a.created_at
			FROM roles AS a WHERE a.is_active = '1'"
        );

        // Add row index
        $datatables->add('DT_RowIndex', function () {
            return 0;
        });

        return $this->output->set_content_type('application/json')
            ->set_output($datatables->generate());
    }

    public function add_permission()
    {
        if ($this->input->method() === 'get') {
            // $data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));

            // $this->load->view('auth/permission/add_permission', $data);
            return $this->templates->render([
                'title' => 'Permissions',
                'type' => 'backend', // auth, frontend, backend
                'uri_segment' => $this->_path,
                'breadcrumb' => [
                    'Auth', 'Manajemen', 'Permissions', 'Add'
                ],
                'page' => $this->_path . 'manage/add_permission',
                'script' => $this->_path . 'manage/js/script_js',
                'style' => $this->_path . 'manage/css/style_css',
                'modals' => [],
            ]);
        } elseif ($this->input->method() === 'post') {
            $new_permission_id = $this->ion_auth_acl->create_permission($this->input->post('perm_key'), $this->input->post('perm_name'));
            if ($new_permission_id) {
                // check to see if we are creating the permission
                // redirect them back to the admin page
                // $this->session->set_flashdata('message', $this->ion_auth->messages());
                // redirect("/permission/permissions", 'refresh');
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Permission berhasil ditambahkan',
                        'data' => null,
                    ]));
            }
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Gagal',
                    'data' => null,
                    'errors' => $this->ion_auth_acl->errors()
                ]));
        }
    }

    public function update_permission()
    {
        $permission_id = $this->input->post('id');
        $permission =   $this->ion_auth_acl->permission($permission_id);

        if ($this->input->method() === 'get') {

            // $this->load->view('auth/permission/edit_permission', $data);
            return $this->templates->render([
                'title' => 'Permissions',
                'type' => 'backend', // auth, frontend, backend
                'permission' => $permission,
                'breadcrumb' => [
                    'Auth', 'Manajemen', 'Permissions', 'Update'
                ],
                'uri_segment' => $this->_path,
                'page' => $this->_path . 'manage/edit_permission',
                'script' => $this->_path . 'manage/js/script_js',
                'style' => $this->_path . 'manage/css/style_css',
                'modals' => [],
            ]);
        } elseif ($this->input->method() === 'post') {
            $additional_data    =   array(
                'perm_name' =>  $this->input->post('perm_name')
            );

            $update_permission = $this->ion_auth_acl->update_permission($permission_id, $this->input->post('perm_key'), $additional_data);
            if ($update_permission) {
                // check to see if we are creating the permission
                // redirect them back to the admin page
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Permission berhasil diubah',
                        'data' => null
                    ]));
            }
            return $this->output->set_content_type('application/json')
                ->set_status_code(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed',
                    'data' => null,
                    'errors' => $this->ion_auth_acl->errors()
                ]));
        }
    }

    public function delete_permission()
    {
        $permission_id = $this->input->post('id');

        if ($this->input->method() === 'post') {
            if ($this->ion_auth_acl->remove_permission($permission_id)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                // redirect("/permission/permissions", 'refresh');
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Permission berhasil dihapus',
                        'data' => null
                    ]));
            } else {
                // echo $this->ion_auth_acl->messages();
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Failed',
                        'data' => null,
                        'errors' => $this->ion_auth_acl->errors()
                    ]));
            }
        }
        redirect($this->_path);
    }

    public function role()
    {
        // $data['groups'] = $this->ion_auth->groups()->result();

        // $this->load->view('auth/permission/groups', $data);
        return $this->templates->render([
            'title' => 'Permissions',
            'type' => 'backend', // auth, frontend, backend
            'breadcrumb' => [
                'Auth', 'Manajemen', 'Permissions', 'Role'
            ],
            'uri_segment' => $this->_path,
            'permissions' => $this->ion_auth_acl->permissions('full'),
            'page' => $this->_path . 'roles/index',
            'script' => $this->_path . 'roles/js/script_js',
            'style' => $this->_path . 'roles/css/style_css',
            'modals' => [],
        ]);
    }

    public function role_permissions($id)
    {
        $group_id = $id;

        if (!$group_id) {
            redirect($this->_path . 'roles');
        }

        if ($this->input->method() === 'post') {
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id  =   str_replace("perm_", "", $k);

                    if ($v == "X")
                        $this->ion_auth_acl->remove_permission_from_group($group_id, $permission_id);
                    else
                        $this->ion_auth_acl->add_permission_to_group($group_id, $permission_id, $v);
                }
            }

            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Permission berhasil diatur',
                    'data' => null
                ]));
        } elseif ($this->input->method() === 'get') {
            return $this->templates->render([
                'title' => 'Permissions',
                'type' => 'backend', // auth, frontend, backend
                'uri_segment' => $this->_path,
                'breadcrumb' => [
                    'Auth', 'Manajemen', 'Permissions', 'Role Access'
                ],
                'permissions' => $this->ion_auth_acl->permissions('full'),
                'group_id' => $group_id,
                'group_permissions' => $this->ion_auth_acl->get_group_permissions($group_id),
                'page' => $this->_path . 'roles/role_permissions',
                'script' => $this->_path . 'roles/js/script_js',
                'style' => $this->_path . 'roles/css/style_css',
                'modals' => [],
            ]);
        }
    }

    // public function users()
    // {
    //     $data['users']  =   $this->ion_auth->users()->result();

    //     $this->load->view('auth/permission/users', $data);
    // }

    // public function manage_user()
    // {
    //     $user_id  =   $this->uri->segment(3);

    //     if (!$user_id) {
    //         $this->session->set_flashdata('message', "No user ID passed");
    //         redirect("/permission/users", 'refresh');
    //     }

    //     $data['user']               =   $this->ion_auth->user($user_id)->row();
    //     $data['user_groups']        =   $this->ion_auth->get_users_groups($user_id)->result();
    //     $data['user_acl']           =   $this->ion_auth_acl->build_acl($user_id);

    //     $this->load->view('auth/permission/manage_user', $data);
    // }

    // public function user_permissions()
    // {
    //     $user_id  =   $this->uri->segment(3);

    //     if (!$user_id) {
    //         $this->session->set_flashdata('message', "No user ID passed");
    //         redirect("/permission/users", 'refresh');
    //     }

    //     if ($this->input->post() && $this->input->post('cancel'))
    //         redirect("/permission/manage_user/{$user_id}", 'refresh');


    //     if ($this->input->post() && $this->input->post('save')) {
    //         foreach ($this->input->post() as $k => $v) {
    //             if (substr($k, 0, 5) == 'perm_') {
    //                 $permission_id  =   str_replace("perm_", "", $k);

    //                 if ($v == "X")
    //                     $this->ion_auth_acl->remove_permission_from_user($user_id, $permission_id);
    //                 else
    //                     $this->ion_auth_acl->add_permission_to_user($user_id, $permission_id, $v);
    //             }
    //         }

    //         redirect("/permission/manage_user/{$user_id}", 'refresh');
    //     }

    //     $user_groups    =   $this->ion_auth_acl->get_user_groups($user_id);

    //     $data['user_id']                =   $user_id;
    //     $data['permissions']            =   $this->ion_auth_acl->permissions('full', 'perm_key');
    //     $data['group_permissions']      =   $this->ion_auth_acl->get_group_permissions($user_groups);
    //     $data['users_permissions']      =   $this->ion_auth_acl->build_acl($user_id);

    //     $this->load->view('auth/permission/user_permissions', $data);
    // }
}
