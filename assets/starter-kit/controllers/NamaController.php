<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

class NamaController extends MY_Controller
{
    private $_path = 'type/nama_role/nama_controller/'; // Contoh 'backend/admin/dashboard'

    public function __construct()
    {
        parent::__construct();
        check_group("nama_role");
        $this->load->model($this->_path . 'M_NamaModel');
        $this->load->library(['upload', 'image_lib']);
    }

    public function index()
    {
        $this->templates->load([
            'title' => '',
            'type' => '', // auth, frontend, backend
            'uri_segment' => $this->_path,
            'page' => $this->_path . 'index',
            'script' => $this->_path . 'index_js',
            'modals' => [
                $this->_path . 'modal/modal_tambah',
                $this->_path . 'modal/modal_ubah',
            ]
        ]);
    }

    public function data()
    {
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query("");

        $datatables->add('DT_Row_Index', function () {
            return 0;
        });

        return $this->output->set_content_type('application/json')
            ->set_output($datatables->generate());
    }

    public function insert()
    {
        $config['upload_path'] = './uploads/nama_subfolder/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload("foto")) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $this->upload->display_errors()
                ]));
        }

        $config['image_library'] = 'gd2';
        $config['source_image'] = $this->upload->data('full_path');
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 200;
        $config['height'] = 150;

        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $this->image_lib->display_errors()
                ]));
        }

        $this->M_NamaModel->insert(
            $this->table,
            [
                'kolom_1' => $this->input->post('kolom_1', true),
                'kolom_2' => $this->input->post('kolom_2', true),
                'foto' => $this->upload->data('file_name'),
                'foto_thumb' => $this->upload->data('raw_name') . '_thumb' . $this->upload->data('file_ext'),
                'is_active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => get_user_id(),
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
                'data' => $this->M_NamaModel->get_where(
                    [
                        'a.id' => $this->input->get('id', true),
                        'a.is_active' => '1'
                    ]
                )
            ]));
    }

    public function update()
    {
        $config['upload_path'] = './uploads/nama_subfolder/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config);
        if ($_FILES['foto']['error'] !== 4) {
            if (file_exists("./uploads/nama_subfolder/{$this->input->post('old_foto')}")) {
                chmod("./uploads/nama_subfolder/{$this->input->post('old_foto')}", 0777);
                chmod("./uploads/nama_subfolder/{$this->input->post('old_foto_thumb')}", 0777);
                unlink("./uploads/nama_subfolder/{$this->input->post('old_foto')}");
                unlink("./uploads/nama_subfolder/{$this->input->post('old_foto_thumb')}");
            }

            if (!$this->upload->do_upload("foto")) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => $this->upload->display_errors()
                    ]));
            }

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->data('full_path');
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
            $config['width'] = 200;
            $config['height'] = 150;

            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => $this->image_lib->display_errors()
                    ]));
            }
        }

        $this->M_NamaModel->update(
            [
                'kolom_1' => $this->input->post('kolom_1', true),
                'kolom_2' => $this->input->post('kolom_2', true),
                'foto' => $_FILES['foto']['error'] === 4
                    ? $this->input->post('old_foto') : $this->upload->data('file_name'),
                'foto_thumb' => $_FILES['foto']['error'] === 4
                    ? $this->input->post('old_foto_thumb') : $this->upload->data('raw_name') . '_thumb' . $this->upload->data('file_ext'),
                'is_active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => get_user_id(),
            ],
            $this->input->get('id', true)
        );
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Updated successfuly'
            ]));
    }

    public function delete()
    {
        $data = $this->M_NamaModel->get_where([
            'a.id' => $this->input->post('id', true),
            'a.is_active' => '1'
        ]);
        if (file_exists("./uploads/subfolder/{$data->foto}")) {
            chmod("./uploads/subfolder/{$data->foto}", 0777);
            chmod("./uploads/subfolder/{$data->foto_thumb}", 0777);
            unlink("./uploads/subfolder/{$data->foto}");
            unlink("./uploads/subfolder/{$data->foto_thumb}");
        }


        $this->M_NamaModel->update(
            [
                'is_active' => '0',
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_user_id(),
            ],
            $this->input->get('id', true)
        );

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Deleted successfuly'
            ]));
    }
}

/* End of file Home.php */
