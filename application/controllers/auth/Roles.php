<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Roles extends MY_Controller
{
    private $_path = 'auth/roles/'; // Contoh 'backend/admin/dashboard'

    /**
     * Roles constructor
     */
    public function __construct()
    {
        parent::__construct();
        role("admin");    // admin, ...
        $this->load->library(['upload', 'form_validation']);  // Load library upload
        $this->lang->load('auth');
    }

    /**
     * Halaman index
     *
     * @return CI_Loader
     */
    public function index(): CI_Loader
    {
        return $this->templates->render([
            'title' => 'Roles',
            'type' => 'backend', // auth, frontend, backend
            'breadcrumb' => [
                'Auth', 'Manajemen', 'Roles'
            ],
            'uri_segment' => $this->_path,
            'page' => $this->_path . 'index',
            'script' => $this->_path . 'js/script_js',
            'style' => $this->_path . 'css/style_css',
            'modals' => [],
        ]);
    }

    /**
     * Keperluan DataTables server-side
     *
     * @return CI_Output
     */
    public function data_role(): CI_Output
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

    /**
     * Create a new group
     */
    public function create_role()
    {
        if ($this->input->method() === 'post') {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                // $this->session->set_flashdata('message', $this->ion_auth->messages());
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Role berhasil ditambahkan',
                        'data' => null
                    ]));
                // redirect("auth", 'refresh');
            } else {
                // $this->session->set_flashdata('message', $this->ion_auth->errors());
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Gagal',
                        'data' => null,
                        'errors' => $this->ion_auth->errors()
                    ]));
            }
        } elseif ($this->input->method() === 'get') {
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

            // $this->_render_page('auth/create_group', $this->data);
            $this->templates->render([
                'title' => 'Create Role',
                'type' => 'backend',
                'breadcrumb' => [
                    'Auth', 'Manajemen', 'Roles', 'Create'
                ],
                'group_name' => $this->data['group_name'],
                'description' => $this->data['description'],
                'uri_segment' => $this->_path,
                'page' => $this->_path . 'create_role',
                'script' => $this->_path . 'js/script_js',
                'style' => $this->_path . 'css/style_css',
                'modals' => []
            ]);
        }
    }

    /**
     * Edit a group
     *
     * @param int|string $id
     */
    public function edit_role($id)
    {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = 'Edit Role';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        // validate form input
        if ($this->input->method() === 'post') {
            $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
                'description' => $_POST['group_description']
            ));

            if ($group_update) {
                // 	$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                // 	redirect("auth", 'refresh');
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Role berhasil diupdate',
                        'data' => null,
                    ]));
            } else {
                // $this->session->set_flashdata('message', $this->ion_auth->errors());
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Gagal',
                        'data' => null,
                        'errors' => $this->ion_auth->errors()
                    ]));
            }
        } elseif ($this->input->method() === 'get') {
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

            // $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
            $this->templates->render([
                'title' => $this->data['title'],
                'type' => 'backend',
                'breadcrumb' => [
                    'Auth', 'Manajemen', 'Roles', 'Edit'
                ],
                'message' => $this->data['message'],
                'group' => $this->data['group'],
                'group_name' => $this->data['group_name'],
                'group_description' => $this->data['group_description'],
                'group_id' => $id,
                'uri_segment' => $this->_path,
                'page' => $this->_path . 'edit_role',
                'script' => $this->_path . 'js/script_js',
                'style' => $this->_path . 'css/style_css',
                'modals' => []
            ]);
        }
    }

    public function delete_role()
    {
        if ($this->input->method() === 'post') {
            if ($this->ion_auth->delete_group($this->input->post('id'))) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Role berhasil dihapus',
                        'data' => null
                    ]));
            }

            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Gagal',
                    'data' => null,
                    'errors' => $this->ion_auth->errors()
                ]));
        }
        redirect('auth', 'refresh');
    }
}
