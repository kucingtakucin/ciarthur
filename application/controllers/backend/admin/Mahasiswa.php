<?php

use Dompdf\Dompdf;
use GuzzleHttp\Client;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpWord\TemplateProcessor;

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends MY_Controller
{
    private $_path = 'backend/admin/mahasiswa/'; // Contoh 'backend/admin/dashboard'

    /**
     * Mahasiswa constructor
     */
    public function __construct()
    {
        parent::__construct();
        check_group("admin");
        $this->load->model($this->_path . 'M_Mahasiswa');   // Load model M_Mahasisw
        $this->load->library(['upload', 'image_lib']);  // Load library upload, image_lib
    }

    /**
     * Halaman index
     *
     * @return CI_Loader
     */
    public function index(): CI_Loader
    {
        return $this->templates->render([
            'title' => 'Mahasiswa',
            'type' => 'backend', // auth, frontend, backend
            'uri_segment' => $this->_path,
            'page' => $this->_path . 'index',
            'script' => $this->_path . 'index_js',
            'modals' => [
                $this->_path . 'modal/modal_tambah',
                $this->_path . 'modal/modal_ubah',
            ],
        ]);
    }

    /**
     * Keperluan DataTables server-side
     *
     * @return CI_Output
     */
    public function data(): CI_Output
    {
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT a.id, a.nim, a.nama, a.angkatan, a.foto_thumb,
            b.nama AS nama_prodi, c.nama AS nama_fakultas,
            a.prodi_id, a.fakultas_id, a.latitude, a.longitude
            FROM mahasiswa AS a
            JOIN prodi AS b ON b.id = a.prodi_id
            JOIN fakultas AS c ON c.id = a.fakultas_id
            WHERE a.is_active = '1'"
        );

        // Add row index
        $datatables->add('DT_Row_Index', function () {
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
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->like('nama', $this->input->get('search'))
                    ->get('fakultas')->result()
            ]));
    }

    /**
     * Keperluan AJAX Select2
     *
     * @return CI_Output
     */
    public function get_prodi(): CI_Output
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->where('fakultas_id', $this->input->get('fakultas_id'))
                    ->like('nama', $this->input->get('search'))
                    ->get('prodi')->result()
            ]));
    }

    /**
     * Keperluan AJAX Leaflet
     * 
     * @return CI_Output
     */
    public function get_latlng(): CI_Output
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->select('nama, latitude, longitude')
                    ->get('mahasiswa')
                    ->result()
            ]));
    }

    /**
     * Keperluan AJAX Leaflet
     *
     * @return CI_Output
     */
    public function get_kecamatan(): CI_Output
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->get('kecamatan')->result()
            ]));
    }

    /**
     * Keperluan CRUD tambah data
     *
     * @return CI_Output
     */
    public function insert(): CI_Output
    {
        $config['upload_path'] = './uploads/mahasiswa/';
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

        $this->M_Mahasiswa->insert(
            [
                'nim' => $this->input->post('nim', true),
                'nama' => $this->input->post('nama', true),
                'prodi_id' => $this->input->post('prodi_id', true),
                'fakultas_id' => $this->input->post('fakultas_id', true),
                'angkatan' => $this->input->post('angkatan', true),
                'latitude' => $this->input->post('latitude', true),
                'longitude' => $this->input->post('longitude', true),
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

    /**
     * Keperluan CRUD get where data
     *
     * @return CI_Output
     */
    public function get_where(): CI_Output
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Found',
                'data' => $this->M_Mahasiswa->get_where(
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
        $config['upload_path'] = './uploads/mahasiswa/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config);
        if ($_FILES['foto']['error'] !== 4) {
            if (file_exists("./uploads/mahasiswa/{$this->input->post('old_foto')}")) {
                chmod("./uploads/mahasiswa/{$this->input->post('old_foto')}", 0777);
                chmod("./uploads/mahasiswa/{$this->input->post('old_foto_thumb')}", 0777);
                unlink("./uploads/mahasiswa/{$this->input->post('old_foto')}");
                unlink("./uploads/mahasiswa/{$this->input->post('old_foto_thumb')}");
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

        $this->M_Mahasiswa->update(
            [
                'nim' => $this->input->post('nim', true),
                'nama' => $this->input->post('nama', true),
                'prodi_id' => $this->input->post('prodi_id', true),
                'fakultas_id' => $this->input->post('fakultas_id', true),
                'angkatan' => $this->input->post('angkatan', true),
                'latitude' => $this->input->post('latitude', true),
                'longitude' => $this->input->post('longitude', true),
                'foto' => $_FILES['foto']['error'] === 4
                    ? $this->input->post('old_foto') : $this->upload->data('file_name'),
                'foto_thumb' => $_FILES['foto']['error'] === 4
                    ? $this->input->post('old_foto_thumb') : $this->upload->data('raw_name') . '_thumb' . $this->upload->data('file_ext'),
                'is_active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => get_user_id(),
            ],
            $this->input->post('id', true)
        );

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
        $data = $this->M_Mahasiswa->get_where([
            'a.id' => $this->input->post('id', true),
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
            $this->input->post('id', true)
        );

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Deleted successfuly',
            ]));
    }

    /**
     * Keperluan export Excel
     *
     * @return void
     */
    public function export_excel(): void
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Data Mahasiswa');
        $spreadsheet->getProperties()->setCreator('Mahasiswa')
            ->setLastModifiedBy('Mahasiswa')
            ->setTitle('Data Mahasiswa')
            ->setSubject('Data Mahasiswa')
            ->setDescription('Data Mahasiswa')
            ->setKeywords('data mahasiswa');

        $spreadsheet->getActiveSheet()->getStyle('B5:G5')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFA65');

        $spreadsheet->getActiveSheet(0)
            ->setCellValue('F2', 'DATA MAHASISWA')
            ->getStyle('D2')
            ->getFont()->setBold(true);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('B5', '#')
            ->getStyle('B5')
            ->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('C5', 'NIM')
            ->getStyle('C5')
            ->getFont()
            ->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('D5', 'NAMA LENGKAP')
            ->getStyle('D5')
            ->getFont()
            ->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('E5', 'ANGKATAN')
            ->getStyle('E5')
            ->getFont()
            ->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('F5', 'PROGRAM STUDI')
            ->getStyle('F5')
            ->getFont()
            ->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('G5', 'FAKULTAS')
            ->getStyle('G5')
            ->getFont()
            ->setBold(true);

        $spreadsheet->getActiveSheet(0)->getStyle('B5:G5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet(0)->getStyle('B5:G5')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(30);

        $columns = ['B', 'C', 'D', 'E', 'F', 'G'];

        foreach ($columns as $column) {
            $spreadsheet->getActiveSheet()
                ->getColumnDimension($column)
                ->setAutoSize(true);
            $spreadsheet->getActiveSheet()
                ->getStyle("{$column}5")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color('000000'));
        }

        $data = $this->M_Mahasiswa->get();

        $no = 0;
        $awal = 6;
        foreach ($data as $datum) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $awal, ++$no);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $awal, $datum->nim);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $awal, $datum->nama);
            $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit('E' . $awal, "{$datum->angkatan}", DataType::TYPE_STRING);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $awal, $datum->nama_prodi);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $awal, $datum->nama_fakultas);

            foreach ($columns as $column) {
                $spreadsheet->getActiveSheet()
                    ->getStyle($column . $awal)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
            }
            $awal++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="data_mahasiswa.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * Keperluan export Word
     *
     * @return void
     */
    public function export_word(): void
    {
        $templateProcessor = new TemplateProcessor('assets/templates/word/template_word.docx');
        // $templateProcessor->setValue('param', 'value');

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=data_mahasiswa.docx");
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
    }

    /**
     * Keperluan export PDF
     *
     * @return void
     */
    public function export_pdf(): void
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->getOptions()->setChroot('assets/templates/pdf');
        $dompdf->loadHtml('hello world');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'potrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('data_mahasiswa.pdf');
    }

    public function get_geojson()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://covid19.karanganyarkab.go.id/assets/maps/map-kab-kra.geojson',
            // You can set any number of default request options.
        ]);
        $response = $client->request('POST', '', [
            'form_params' => [
                'field_name' => 'abc',
                'other_field' => '123',
                'nested_field' => [
                    'nested' => 'hello'
                ]
            ]
        ]);

        return $this->output->set_content_type('application/json')
            ->set_output($response->getBody());
    }
}

/* End of file Mahasiswa.php */
