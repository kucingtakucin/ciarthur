<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class Prodi extends MY_Controller
{
    private $_path = 'backend/prodi/'; // Contoh 'backend/dashboard/ / 'frontend/home/'

    /**
     * NamaController constructor
     */
    public function __construct()
    {
        parent::__construct();
        // Salah satu saja, role atau permission
        has_permission('access-prodi');
        //=========================================================//


        $this->load->model($this->_path . 'M_Prodi');   // Load model
        $this->load->library(['upload', 'form_validation']);  // Load library upload
    }

    /**
     * Halaman index
     *
     * @return CI_Loader
     */
    public function index(): CI_Loader
    {
        method('get');
        //=========================================================//        

        return $this->templates->render([
            'title' => 'Prodi',
            'type' => 'backend', // auth, frontend, backend
            'uri_segment' => $this->_path,
            'breadcrumb' => [
                'Backend', 'Referensi', 'Program Studi'
            ],
            'page' => 'contents/' . $this->_path . 'index',
            'script' => 'contents/' . $this->_path . 'js/script_js',
            'style' => 'contents/' . $this->_path . 'css/style_css',
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
            "SELECT a.id, a.nama, a.fakultas_id,
            (SELECT b.nama FROM fakultas AS b WHERE b.id = a.fakultas_id) AS nama_fakultas,
            a.created_at FROM prodi AS a
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
     * Keperluan AJAX Select2
     *
     * @return CI_Output
     */
    public function get_fakultas(): CI_Output
    {
        method('get');
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->like('nama', $this->input->get('search'))
                    ->get_where('fakultas', ['is_active' => '1'])->result()
            ]));
    }

    /**
     * Keperluan validasi server-side
     */
    private function _validator()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nama', 'nama prodi', 'required|trim');
        $this->form_validation->set_rules('fakultas_id', 'fakultas', 'required|trim');
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
        has_permission('create-prodi');
        method('post');
        $this->_validator();
        //=========================================================//

        $this->db->trans_begin();   // Begin transaction
        $insert = $this->M_Prodi->insert(
            [
                'nama' => $this->input->post('nama', true),
                'fakultas_id' => $this->input->post('fakultas_id', true),
                'is_active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => get_user_id(),
            ]
        );

        if (!$this->db->trans_status() || !$insert) {   // Check transaction status
            $this->db->trans_rollback();    // Rollback transaction
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
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
                'data' => $this->M_Prodi->get_where(
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
        has_permission('update-prodi');
        method('post');
        $this->_validator();
        //=========================================================//

        $this->db->trans_begin();   // Begin transaction
        $update = $this->M_Prodi->update(
            [
                'nama' => $this->input->post('nama', true),
                'fakultas_id' => $this->input->post('fakultas_id', true),
                'is_active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => get_user_id(),
            ],
            $this->input->post('id', true)
        );

        if (!$this->db->trans_status() || !$update) {   // Check transaction status
            $this->db->trans_rollback();    // Rollback transaction
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
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
        has_permission('delete-prodi');
        method('post');
        //=========================================================//

        $this->db->trans_begin();   // Begin transaction
        $delete = $this->M_Prodi->update(
            [
                'is_active' => '0',
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_user_id()
            ],
            $this->input->post('id', true)
        );

        if (!$this->db->trans_status() || !$delete) {   // Check transaction status
            $this->db->trans_rollback();    // Rollback transaction
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
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
}

/* End of file NamaController.php */
