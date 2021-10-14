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
        $this->load->library(['upload', 'image_lib', 'ion_auth']);  // Load library upload, image_lib
    }

    public function index_get()
    {
        echo "Codeigniter <b>Rest-Server</b> <i>v3.1</i> <br><br>";
        echo "All <b>GET</b> <i>/all</i> <br>";
        echo "Get Where <b>GET</b>  <i>/get_where?id={{id}}</i> <br>";
        echo "Insert <b>POST</b>  <i>/insert</i> <br>";
        echo "Update <b>PUT</b>  <i>/update</i> <br>";
        echo "Delete <b>DELETE</b>  <i>/delete</i> <br>";
    }

    public function all_get()
    {
        $this->_check_authorization(function () {
            $data = $this->M_Mahasiswa->get();
            if ($data) {
                return $this->response([
                    'status' => true,
                    'data' => $data
                ], 200);
            }
            return $this->response([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        });
    }

    public function get_where_get()
    {
        $this->_check_authorization(function () {
            $data = $this->M_Mahasiswa->get_where([
                'a.id' => $this->get('id', true)
            ]);
            if ($data) {
                return $this->response([
                    'status' => true,
                    'data' => $data
                ], 200);
            }
            return $this->response([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        });
    }

    public function insert_post()
    {
        $this->_check_authorization(function () {
            $config['upload_path'] = './uploads/mahasiswa/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            $config['remove_spaces'] = true;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload("foto")) {
                return $this->response([
                    'status' => false,
                    'message' => $this->upload->display_errors()
                ], 404);
            }

            $this->M_Mahasiswa->insert(
                [
                    'nim' => $this->post('nim', true),
                    'nama' => $this->post('nama', true),
                    'prodi_id' => $this->post('prodi_id', true),
                    'fakultas_id' => $this->post('fakultas_id', true),
                    'angkatan' => $this->post('angkatan', true),
                    'foto' => $this->upload->data('file_name'),
                    'is_active' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => get_user_id(),
                ]
            );

            return $this->response([
                'status' => true,
                'message' => 'Created successfuly'
            ], 200);
        });
    }

    public function update_put()
    {
        $this->_check_authorization(function () {
            $config['upload_path'] = './uploads/mahasiswa/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            $config['remove_spaces'] = true;
            $this->upload->initialize($config);
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== 4) {
                if (file_exists("./uploads/mahasiswa/{$this->put('old_foto')}")) {
                    unlink("./uploads/mahasiswa/{$this->put('old_foto')}");
                }

                if (!$this->upload->do_upload("foto")) {
                    return $this->response([
                        'status' => false,
                        'message' => $this->upload->display_errors()
                    ], 404);
                }
            }

            $this->M_Mahasiswa->update(
                [
                    'nim' => $this->put('nim', true),
                    'nama' => $this->put('nama', true),
                    'prodi_id' => $this->put('prodi_id', true),
                    'fakultas_id' => $this->put('fakultas_id', true),
                    'angkatan' => $this->put('angkatan', true),
                    'foto' => isset($_FILES['foto']) && $_FILES['foto']['error'] === 4
                        ? $this->put('old_foto') : $this->upload->data('file_name'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => get_user_id(),
                ],
                $this->put('id', true)
            );

            return $this->response([
                'status' => true,
                'message' => 'Updated successfuly',
            ], 200);
        });
    }

    public function delete_delete()
    {
        $this->_check_authorization(function () {
            $data = $this->M_Mahasiswa->get_where([
                'a.id' => $this->delete('id', true),
                'a.is_active' => '1'
            ]);
            if (file_exists("./uploads/mahasiswa/{$data->foto}")) {
                unlink("./uploads/mahasiswa/{$data->foto}");
            }

            $this->M_Mahasiswa->update(
                [
                    'is_active' => '0',
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'deleted_by' => get_user_id()
                ],
                $this->delete('id', true)
            );

            return $this->response([
                'status' => true,
                'message' => 'Deleted successfuly',
            ], 200);
        });
    }

    private function _check_authorization(Closure $callback)
    {
        if (
            array_key_exists('Authorization', $this->input->request_headers()) &&
            !empty($this->input->request_headers()['Authorization'])
        ) {
            $headers = explode(" ", $this->input->request_headers()["Authorization"]);
            $bearer_token = end($headers);
            $decodedToken = validateTimestamp($bearer_token);

            // return response if token is valid
            if ($decodedToken->status) {
                // $this->set_response($decodedToken, RestController::HTTP_OK);
                if (is_callable($callback)) {
                    $callback();
                }
            } else {
                return $this->response([
                    'status' => false,
                    'message' => $decodedToken->message,
                    'exception' => @$decodedToken->exception
                ], RestController::HTTP_BAD_REQUEST);
            }
        }

        $this->response([
            'status' => false,
            'message' => "Unauthorized"
        ], RestController::HTTP_UNAUTHORIZED);
    }
}
