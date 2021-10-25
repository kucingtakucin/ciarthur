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

class NamaController extends MY_Controller
{
	private $_path = 'backend/apa/'; // Contoh 'backend/dashboard/ / 'frontend/home/'

	/**
	 * Mahasiswa constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Salah satu saja, role atau permission
		role("apa");    // admin, ...
		has_permission('access-apa');
		//=========================================================//

		$this->load->model($this->_path . 'M_NamaModel');   // Load model
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
			'title' => 'Judul',
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', 'Apa'
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
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
		//=========================================================//

		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT * FROM nama_tabel AS a
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
	 * Keperluan validasi server-side
	 */
	private function _validator()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kolom_1', 'kolom_1', 'required|trim');
		$this->form_validation->set_rules('kolom_2', 'kolom_2', 'required|trim');

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
		has_permission('create-apa');
		method('post');
		$this->_validator();
		//=========================================================//

		$config['upload_path'] = './uploads/apa/';
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
		$this->M_NamaModel->insert(
			[
				'kolom_1' => $this->input->post('kolom_1', true),
				'kolom_2' => $this->input->post('kolom_2', true),
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
		//=========================================================//

		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => true,
				'message' => 'Found',
				'data' => $this->M_NamaModel->get_where(
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
		has_permission('update-apa');
		method('post');
		$this->_validator();
		//=========================================================//

		$config['upload_path'] = './uploads/apa/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);
		if ($_FILES['foto']['error'] !== 4) {
			if (file_exists("./uploads/apa/{$this->input->post('old_foto')}")) {
				unlink("./uploads/apa/{$this->input->post('old_foto')}");
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
				'kolom_1' => $this->input->post('kolom_1', true),
				'kolom_2' => $this->input->post('kolom_2', true),
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
		has_permission('delete-apa');
		method('post');
		//=========================================================//

		$data = $this->M_NamaModel->get_where([
			'a.id' => $this->input->post('id', true),
			'a.is_active' => '1'
		]);
		if (file_exists("./uploads/apa/{$data->foto}")) {
			unlink("./uploads/apa/{$data->foto}");
		}

		$this->db->trans_begin();
		$this->M_NamaModel->update(
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
}

/* End of file Mahasiswa.php */
