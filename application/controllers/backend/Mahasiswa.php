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
    private $_path = 'backend/mahasiswa/'; // Contoh 'backend/admin/dashboard'

    /**
     * Mahasiswa constructor
     */
    public function __construct()
    {
        parent::__construct();
        has_permission('access-mahasiswa');
        $this->load->model($this->_path . 'M_Mahasiswa');   // Load model M_Mahasisw
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
        return $this->templates->render([
            'title' => 'Mahasiswa',
            'type' => 'backend', // auth, frontend, backend
            'uri_segment' => $this->_path,
            'page' => 'contents/' . $this->_path . 'index',
            'script' => 'contents/' . $this->_path . 'js/script_js',
            'style' => 'contents/' . $this->_path . 'css/style_css',
            'modals' => [
                'contents/' . $this->_path . 'modal/tambah',
                'contents/' . $this->_path . 'modal/ubah',
                'contents/' . $this->_path . 'modal/import',
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
        method('get');
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT a.id, a.nim, a.nama, a.angkatan, a.foto,
            (SELECT b.nama FROM prodi AS b WHERE b.id = a.prodi_id) AS nama_prodi,
            (SELECT c.nama FROM fakultas AS c WHERE c.id = a.fakultas_id) AS nama_fakultas,
            a.created_at, a.prodi_id, a.fakultas_id, a.latitude, a.longitude
            FROM mahasiswa AS a
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
        method('get');
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
        method('get');
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
        method('get');
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'data' => $this->db->get('kecamatan')->result()
            ]));
    }

    /**
     * Keperluan validasi server-side
     */
    public function validator()
    {
        $this->form_validation->set_rules('nim', 'NIM', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('prodi_id', 'Prodi', 'required|trim');
        $this->form_validation->set_rules('fakultas_id', 'Fakultas', 'required|trim');
        $this->form_validation->set_rules('angkatan', 'Angkatan', 'required|trim');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|trim');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|trim');
        // $this->form_validation->set_rules('foto', 'Foto', 'required');
    }

    /**
     * Keperluan CRUD tambah data
     *
     * @return CI_Output
     */
    public function insert(): CI_Output
    {
        has_permission('create-mahasiswa');
        method('post');
        $this->validator();
        if (!$this->form_validation->run()) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Please check your input again!',
                    'errors' => validation_errors()
                ]));
        }

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

        $this->db->trans_begin();
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
                'is_active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => get_user_id(),
            ]
        );

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed',
                    'errors' => $this->db->error()
                ]));
        }

        $this->db->trans_commit();
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
        has_permission('update-mahasiswa');
        method('post');
        $this->validator();
        if (!$this->form_validation->run()) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Please check your input again!',
                    'errors' => validation_errors()
                ]));
        }

        $config['upload_path'] = './uploads/mahasiswa/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config);
        if ($_FILES['foto']['error'] !== 4) {
            if (file_exists("./uploads/mahasiswa/{$this->input->post('old_foto')}")) {
                unlink("./uploads/mahasiswa/{$this->input->post('old_foto')}");
            }

            if (!$this->upload->do_upload("foto")) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => $this->upload->display_errors()
                    ]));
            }
        }

        $this->db->trans_begin();
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
                'is_active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => get_user_id(),
            ],
            $this->input->post('id', true)
        );

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed',
                    'errors' => $this->db->error()
                ]));
        }

        $this->db->trans_commit();

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
        has_permission('delete-mahasiswa');
        method('post');

        $data = $this->M_Mahasiswa->get_where([
            'a.id' => $this->input->post('id', true),
            'a.is_active' => '1'
        ]);
        if (file_exists("./uploads/mahasiswa/{$data->foto}")) {
            unlink("./uploads/mahasiswa/{$data->foto}");
        }

        $this->db->trans_begin();
        $this->M_Mahasiswa->update(
            [
                'is_active' => '0',
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_user_id()
            ],
            $this->input->post('id', true)
        );

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed',
                    'errors' => $this->db->error()
                ]));
        }
        $this->db->trans_commit();

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
        method('get');
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
     * Keperluan import xlsx
     * 
     * @return void
     */
    public function import_excel()
    {
        method('post');
        if (is_uploaded_file($_FILES['import_file_excel']['tmp_name'])) {
            if (!in_array(mime_content_type($_FILES['import_file_excel']['tmp_name']), ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Ekstensi file tidak sesuai! Wajib <b>.xlsx</b>',
                        'mimetype' => mime_content_type($_FILES['import_file_excel']['tmp_name'])
                    ]));
            }

            $spreadsheet = IOFactory::load($_FILES['import_file_excel']['tmp_name']);
            $data = $spreadsheet->getActiveSheet()->toArray();

            if (
                !($data[3][1] === 'NO' && $data[3][2] === 'NIM' && $data[3][3] === 'NAMA LENGKAP' && $data[3][4] === 'ANGKATAN'
                    && $data[3][5] === 'PROGRAM STUDI' && $data[3][6] === 'FAKULTAS' && $data[3][7] === 'LATITUDE' && $data[3][8] === 'LONGITUDE')
            ) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Format tidak sesuai! mohon disesuaikan dengan template',
                    ]));
            }

            for ($i = 4; $i < count($data); $i++) {
                if (
                    $data[$i][1] && $data[$i][1] !== 'NO' && $data[$i][2] && $data[$i][2] !== 'NIM'
                    && $data[$i][3] !== 'NAMA LENGKAP' && $data[$i][4] !== 'ANGKATAN' && $data[$i][5] !== 'PROGRAM STUDI'
                    && $data[$i][6] !== 'FAKULTAS' && $data[$i][7] !== 'LATITUDE' && $data[$i][8] !== 'LONGITUDE'
                ) {
                    $this->M_Mahasiswa->insert(
                        [
                            'nim' => $data[$i][2] ?? null,
                            'nama' => $data[$i][3] ?? null,
                            'prodi_id' => $this->db->get_where('prodi', ['nama' => strtoupper($data[$i][5])])->row()->id ?? null,
                            'fakultas_id' => $this->db->get_where('fakultas', ['nama' => strtoupper($data[$i][6])])->row()->id ?? null,
                            'angkatan' => $data[$i][4] ?? null,
                            'latitude' => $data[$i][7] ?? null,
                            'longitude' =>  $data[$i][8] ?? null,
                            'foto' => null,
                            'is_active' => '1',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => get_user_id(),
                        ]
                    );
                }
            }

            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Berhasil melakukan import'
                ]));
        }
        return $this->output->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Gagal melakukan import! Ada kesalahan'
            ]));
    }

    /**
     * Keperluan download template excel
     * 
     * @return void
     */
    public function download_template_excel()
    {
        method('get');
        $spreadsheet = IOFactory::load(FCPATH . 'assets/templates/excel/template_daftar_mahasiswa.xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="template_daftar_mahasiswa.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Keperluan export Word
     *
     * @return void
     */
    public function export_word(): void
    {
        method('get');
        $templateProcessor = new TemplateProcessor('assets/templates/word/template_word.docx');
        // $templateProcessor->setValue('param', 'value');

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=data_mahasiswa.docx");
        header('Cache-Control: max-age=0');
        $templateProcessor->save('php://output');
        exit;
    }

    /**
     * Keperluan export PDF
     *
     * @return void
     */
    public function export_pdf(): void
    {
        method('get');
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
        method('get');
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
