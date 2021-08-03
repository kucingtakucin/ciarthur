<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mahasiswa extends RestController
{
    private $_path = 'api/mahasiswa/';

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model($this->_path . 'M_Mahasiswa');
    }

    public function all_mahasiswa_get()
    {
        $data = $this->M_Mahasiswa->get();
        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data
            ], 200);
        }
        $this->response([
            'status' => false,
            'message' => 'Not found'
        ], 404);
    }

    public function find_mahasiswa_get()
    {
        $data = $this->M_Mahasiswa->get_where([
            'a.id' => $this->get('id', true)
        ]);
        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data
            ], 200);
        }
        $this->response([
            'status' => false,
            'message' => 'Not found'
        ], 404);
    }

    public function insert_mahasiswa_post()
    {
    }
}
