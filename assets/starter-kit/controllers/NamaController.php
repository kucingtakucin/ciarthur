<?php

defined('BASEPATH') or exit('No direct script access allowed');

class NamaController extends MY_Controller
{
    private $path = 'type/nama_role/nama_controller/'; // Contoh 'backend/admin/dashboard'
    private $table = 'nama_table';

    public function __construct()
    {
        parent::__construct();
        check_group("nama_role");
        $this->load->model($this->path . 'M_NamaModel');
    }

    public function index()
    {
        $this->templates->load([
            'title' => '',
            'type' => '', // auth, frontend, backend
            'uri_segment' => $this->path,
            'page' => $this->path . 'index',
            'script' => $this->path . 'index_js',
            'modals' => [
                $this->path . 'modal/modal_tambah',
                $this->path . 'modal/modal_ubah',
            ]
        ]);
    }

    public function data()
    {
        $this->M_NamaModel->generate_table();
    }

    public function insert()
    {
        $this->M_NamaModel->insert(
            $this->table,
            [
                'kolom_1' => $this->input->post('kolom_1', true),
                'kolom_2' => $this->input->post('kolom_2', true),
                'is_active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->ion_auth_model->user()->id,
            ]
        );

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Created successfuly'
            ]));
    }

    public function get_where()
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Found',
                'data' => $this->M_NamaModel->get_where($this->_table, [
                    'id' => $this->input->get('id', true),
                    'is_active' => '1'
                ])
            ]));
    }

    public function update()
    {
        $this->M_NamaModel->update($this->_table, [], $this->input->get('id', true));
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Updated successfuly'
            ]));
    }

    public function delete()
    {
        $this->M_NamaModel->update($this->table, [
            'is_active' => '0',
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->ion_auth_model->user()->id,
        ], $this->input->get('id', true));

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Deleted successfuly'
            ]));
    }
}

/* End of file Home.php */
