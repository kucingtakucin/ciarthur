<?php

use Dompdf\Dompdf;
use Endroid\QrCode\Color\Color as QrCodeColor;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\SvgWriter;
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpWord\TemplateProcessor;
use Picqer\Barcode\BarcodeGeneratorPNG;

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends MY_Controller
{
	/**
	 * Mahasiswa constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'mahasiswa';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

		has_permission("access-{$this->_name}");
		// ================================================ //

		$this->load->model($this->_path . 'Crud');   // Load CRUD model
		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
	}

	/**
	 * Halaman index
	 *
	 */
	public function index()
	{
		method('get');
		// ================================================ //

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', 'Menu', ucwords($this->_name)
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [],
			'qrcode' => $this->generate_qrcode(),
			'barcode' => $this->generate_barcode()
		];

		render($config);
	}

	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	/**
	 * Keperluan DataTables server-side
	 *
	 */
	public function data()
	{
		method('post');
		// ================================================ //

		response($this->Datatable->list());
	}

	//=============================================================//
	//======================== VALIDATOR =========================//
	//=============================================================//

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator($status = null)
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($status === 'inline') $this->form_validation->set_rules('value', post('name'), 'required|trim');
		else {
			$this->form_validation->set_rules('nim', 'NIM', 'required|trim');
			$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
			$this->form_validation->set_rules('prodi_id', 'Prodi', 'required|trim');
			$this->form_validation->set_rules('fakultas_id', 'Fakultas', 'required|trim');
			$this->form_validation->set_rules('angkatan', 'Angkatan', 'required|trim');
			$this->form_validation->set_rules('latitude', 'Latitude', 'required|trim');
			$this->form_validation->set_rules('longitude', 'Longitude', 'required|trim');
			// $this->form_validation->set_rules('foto', 'Foto', 'required');
		}

		if (!$this->form_validation->run()) {
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array(),
				'last_query' => $this->db->last_query(),
			], 422);
		}
	}

	//=============================================================//
	//=========================== CRUD ============================//
	//=============================================================//

	/**
	 * Keperluan CRUD tambah data
	 *
	 */
	public function insert()
	{
		has_permission("create-{$this->_name}");
		method('post');
		$this->_validator();
		// ================================================ //

		$config['upload_path'] = './uploads/mahasiswa/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload("foto")) {
			response([
				'status' => false,
				'message' => $this->upload->display_errors('', '')
			], 404);
		}

		$this->db->trans_begin();

		$insert = $this->Crud->insert(
			[
				'uuid' => uuid(),
				'nim' => post('nim', true),
				'nama' => post('nama', true),
				'prodi_id' => post('prodi_id', true),
				'fakultas_id' => post('fakultas_id', true),
				'angkatan' => post('angkatan', true),
				'latitude' => post('latitude', true),
				'longitude' => post('longitude', true),
				'foto' => $this->upload->data('file_name'),
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		if (!$insert || !$this->db->trans_status()) {
			$this->db->trans_rollback();
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();

		response([
			'status' => true,
			'message' => 'Created successfuly',
			'last_query' => $this->db->last_query(),
		], 200);
	}

	/**
	 * Keperluan CRUD get where data
	 *
	 */
	public function get_where()
	{
		method('get');
		// ================================================ //

		response([
			'status' => true,
			'message' => 'Found',
			'data' => $this->Crud->get_where(
				[
					'a.uuid' => post('uuid', true),
					'a.is_active' => '1'
				]
			),
			'last_query' => $this->db->last_query()
		], 200);
	}

	/**
	 * Keperluan CRUD update data
	 *
	 */
	public function update()
	{
		has_permission("update-{$this->_name}");
		method('post');
		$this->_validator();
		// ================================================ //

		$config['upload_path'] = './uploads/mahasiswa/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 5120;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);

		if ($_FILES['foto']['error'] !== 4) {
			if (file_exists("./uploads/mahasiswa/" . post('old_foto'))) {
				unlink("./uploads/mahasiswa/" . post('old_foto'));
			}

			if (!$this->upload->do_upload("foto")) {
				response([
					'status' => false,
					'message' => $this->upload->display_errors('', ''),
				], 500);
			}
		}

		$this->db->trans_begin();

		$update = $this->Crud->update(
			[
				'uuid' => uuid(),
				'nim' => post('nim', true),
				'nama' => post('nama', true),
				'prodi_id' => post('prodi_id', true),
				'fakultas_id' => post('fakultas_id', true),
				'angkatan' => post('angkatan', true),
				'latitude' => post('latitude', true),
				'longitude' => post('longitude', true),
				'foto' => $_FILES['foto']['error'] === 4
					? post('old_foto') : $this->upload->data('file_name'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'uuid' => post('uuid', true)
			]
		);

		if (!$update || !$this->db->trans_status()) {
			$this->db->trans_rollback();
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();

		response([
			'status' => true,
			'message' => 'Updated successfuly',
			'last_query' => $this->db->last_query(),
		], 200);
	}

	/**
	 * Keperluan CRUD delete data
	 *
	 */
	public function delete()
	{
		has_permission("delete-{$this->_name}");
		method('post');
		// ================================================ //

		$data = $this->Crud->get_where([
			'a.uuid' => post('uuid', true),
			'a.is_active' => '1'
		]);

		if (file_exists("./uploads/mahasiswa/{$data->foto}")) {
			unlink("./uploads/mahasiswa/{$data->foto}");
		}

		$this->db->trans_begin();

		$delete = $this->Crud->update(
			[
				'is_active' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			],
			[
				'uuid' => post('uuid', true)
			]
		);

		if (!$delete || !$this->db->trans_status()) {
			$this->db->trans_rollback();
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
			'last_query' => $this->db->last_query(),
		]);
	}

	/**
	 * Keperluan CRUD ubah data inline
	 *
	 */
	public function inline()
	{
		has_permission("update-{$this->_name}");
		method('post');
		$this->_validator('inline');
		//=========================================================//

		$this->db->trans_begin();		// Begin transaction

		$update = $this->Crud->update(
			[
				'uuid' => uuid(),
				post('name') => post('value'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'uuid' => post('uuid')
			]
		);

		if (!$update || !$this->db->trans_status()) {    // Check transaction status
			$this->db->trans_rollback();		// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error(),
				'last_query' => $this->db->last_query(),
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly',
			'last_query' => $this->db->last_query(),
		]);
	}

	// =============================================================================== //
	// =============================================================================== //

	/**
	 * Keperluan AJAX Select2
	 *
	 */
	public function ajax_get_fakultas()
	{
		method('get');

		response([
			'status' => true,
			'data' => $this->db->like('nama', get('search'))
				->get_where('fakultas', ['is_active' => '1'])->result()
		]);
	}

	/**
	 * Keperluan AJAX Select2
	 *
	 */
	public function ajax_get_prodi()
	{
		method('get');

		response([
			'status' => true,
			'data' => $this->db->where('fakultas_id', get('fakultas_id'))
				->like('nama', get('search'))
				->get('prodi')->result()
		]);
	}

	/**
	 * Keperluan AJAX Leaflet
	 * 
	 */
	public function ajax_get_latlng()
	{
		method('get');

		response([
			'status' => true,
			'data' => $this->db->select('nama, latitude, longitude')
				->get('mahasiswa')
				->result()
		]);
	}

	/**
	 * Keperluan AJAX Leaflet
	 *
	 */
	public function ajax_get_kecamatan()
	{
		method('get');

		response([
			'status' => true,
			'data' => $this->db->get('kecamatan')->result()
		]);
	}

	public function ajax_get_geojson()
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

		echo $response->getBody();
	}

	// =============================================================================== //
	// =============================================================================== //

	public function generate_qrcode()
	{
		method('get');

		$writer = new SvgWriter();

		// Create QR code
		$qrCode = QrCode::create('Ciarthur')
			->setEncoding(new Encoding('UTF-8'))
			->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
			->setSize(150)
			->setMargin(5)
			->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
			->setForegroundColor(new QrCodeColor(0, 0, 0))
			->setBackgroundColor(new QrCodeColor(255, 255, 255));

		// Create generic logo
		// $logo = Logo::create(base_url("assets/codeigniter.svg"))
		// 	->setResizeToWidth(50);

		// Create generic label
		$label = Label::create('Label')
			->setTextColor(new QrCodeColor(255, 0, 0));

		$result = $writer->write($qrCode, null, $label);
		return $result->getDataUri();
	}

	public function generate_barcode()
	{
		$generator = new BarcodeGeneratorPNG();
		return base64_encode($generator->getBarcode('081234567890', $generator::TYPE_CODE_128));
	}

	/**
	 * Keperluan export Excel
	 *
	 * @return void
	 */
	public function export_excel(): void
	{
		method('post');
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
			->getStyle('F2')
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

		$data = $this->Crud->get();

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
		header('Filename: data_mahasiswa.xlsx');
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
		has_permission("create-{$this->_name}");
		method('post');

		if (is_uploaded_file($_FILES['file_excel']['tmp_name'])) {
			if (!in_array(mime_content_type($_FILES['file_excel']['tmp_name']), ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
				response([
					'status' => false,
					'message' => 'Ekstensi file tidak sesuai! Wajib <b>.xlsx</b>',
					'mimetype' => mime_content_type($_FILES['file_excel']['tmp_name'])
				], 404);
			}

			$spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
			$data = $spreadsheet->getActiveSheet()->toArray();

			if (
				!($data[3][1] === 'NO' && $data[3][2] === 'NIM' && $data[3][3] === 'NAMA LENGKAP' && $data[3][4] === 'ANGKATAN'
					&& $data[3][5] === 'PROGRAM STUDI' && $data[3][6] === 'FAKULTAS' && $data[3][7] === 'LATITUDE' && $data[3][8] === 'LONGITUDE')
			) {
				response([
					'status' => false,
					'message' => 'Format tidak sesuai! mohon disesuaikan dengan template',
				], 500);
			}

			for ($i = 4; $i < count($data); $i++) {
				if (
					$data[$i][1] && $data[$i][1] !== 'NO' && $data[$i][2] && $data[$i][2] !== 'NIM'
					&& $data[$i][3] !== 'NAMA LENGKAP' && $data[$i][4] !== 'ANGKATAN' && $data[$i][5] !== 'PROGRAM STUDI'
					&& $data[$i][6] !== 'FAKULTAS' && $data[$i][7] !== 'LATITUDE' && $data[$i][8] !== 'LONGITUDE'
				) {
					$this->Crud->insert(
						[
							'uuid' => uuid(),
							'nim' => $data[$i][2] ?? null,
							'nama' => $data[$i][3] ?? null,
							'prodi_id' => $this->db->get_where('prodi', ['nama' => strtoupper($data[$i][5])])->row()->id ?? null,
							'fakultas_id' => $this->db->get_where('fakultas', ['nama' => strtoupper($data[$i][6])])->row()->id ?? null,
							'angkatan' => $data[$i][4] ?? null,
							'latitude' => $data[$i][7] ?? null,
							'longitude' =>  $data[$i][8] ?? null,
							'foto' => null,
							'is_active' => '1',
							'created_at' => now(),
							'created_by' => get_user_id(),
						]
					);
				}
			}

			response([
				'status' => true,
				'message' => 'Berhasil melakukan import'
			]);
		}
		response([
			'status' => false,
			'message' => 'You did not select a file to upload.',
		], 500);
	}

	/**
	 * Keperluan download template excel
	 * 
	 * @return void
	 */
	public function download_template_excel()
	{
		method('post');

		$spreadsheet = IOFactory::load(FCPATH . 'assets/templates/excel/template_daftar_mahasiswa.xlsx');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="template_daftar_mahasiswa.xlsx"');
		header('Filename: template_daftar_mahasiswa.xlsx');
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
		method('post');
		$templateProcessor = new TemplateProcessor('assets/templates/word/template_word.docx');
		// $templateProcessor->setValue('param', 'value');

		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=data_mahasiswa.docx");
		header('Filename: data_mahasiswa.docx');
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
		method('post');
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
