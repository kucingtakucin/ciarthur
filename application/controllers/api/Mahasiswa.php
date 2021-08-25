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

    public function all_get()
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

    public function get_where_get()
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

    public function insert_post()
    {
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

        $config['image_library'] = 'gd2';
        $config['source_image'] = $this->upload->data('full_path');
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 200;
        $config['height'] = 150;

        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            return $this->response([
                'status' => false,
                'message' => $this->image_lib->display_errors()
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
                'foto_thumb' => $this->upload->data('raw_name') . '_thumb' . $this->upload->data('file_ext'),
                'is_active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => get_user_id(),
            ]
        );

        return $this->response([
            'status' => true,
            'message' => 'Created successfuly'
        ], 200);
    }

    public function update_put()
    {
        $config['upload_path'] = './uploads/mahasiswa/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config);
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== 4) {
            if (file_exists("./uploads/mahasiswa/{$this->put('old_foto')}")) {
                chmod("./uploads/mahasiswa/{$this->put('old_foto')}", 0777);
                chmod("./uploads/mahasiswa/{$this->put('old_foto_thumb')}", 0777);
                unlink("./uploads/mahasiswa/{$this->put('old_foto')}");
                unlink("./uploads/mahasiswa/{$this->put('old_foto_thumb')}");
            }

            if (!$this->upload->do_upload("foto")) {
                return $this->response([
                    'status' => false,
                    'message' => $this->upload->display_errors()
                ], 404);
            }

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->data('full_path');
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
            $config['width'] = 200;
            $config['height'] = 150;

            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {
                return $this->response([
                    'status' => false,
                    'message' => $this->image_lib->display_errors()
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
                'foto_thumb' => isset($_FILES['foto']) && $_FILES['foto']['error'] === 4
                    ? $this->put('old_foto_thumb') : $this->upload->data('raw_name') . '_thumb' . $this->upload->data('file_ext'),
                'is_active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => get_user_id(),
            ],
            $this->put('id', true)
        );

        return $this->response([
            'status' => true,
            'message' => 'Updated successfuly',
        ], 200);
    }

    public function delete_delete()
    {
        $data = $this->M_Mahasiswa->get_where([
            'a.id' => $this->delete('id', true),
            'a.is_active' => '1'
        ]);
        if (file_exists("./uploads/mahasiswa/{$data->foto}")) {
            chmod("./uploads/mahasiswa/{$data->foto}", 0777);
            chmod("./uploads/mahasiswa/{$data->foto_thumb}", 0777);
            unlink("./uploads/mahasiswa/{$data->foto}");
            unlink("./uploads/mahasiswa/{$data->foto_thumb}");
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
    }
}
