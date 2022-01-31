<?php

use Dompdf\Dompdf;
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
	/**
	 * NamaController constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'apa';
		$this->_path = "backend/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'
		//=========================================================//

		// Salah satu saja, role atau permission
		role("apa");    // admin, ...
		has_permission("access-{$this->_name}");
		//=========================================================//

		$this->load->model($this->_path . 'Datatable');   // Load Datatable model
		$this->load->model($this->_path . 'Crud');   // Load Crud model
	}

	/**
	 * Halaman index
	 *
	 */
	public function index()
	{
		method('get');
		//=========================================================//

		$config = [
			'title' => ucwords($this->_name),
			'type' => 'backend', // auth, frontend, backend
			'uri_segment' => $this->_path,
			'breadcrumb' => [
				'Backend', ucwords($this->_name)
			],
			'page' => 'contents/' . $this->_path . 'index',
			'script' => 'contents/' . $this->_path . 'js/script.js.php',
			'style' => 'contents/' . $this->_path . 'css/style.css.php',
			'modals' => [
				// 'contents/' . $this->_path . 'modal/tambah',
				// 'contents/' . $this->_path . 'modal/ubah',
				// 'contents/' . $this->_path . 'modal/import',
			],
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
		method('get');
		//=========================================================//

		response($this->Datatable->list());
	}

	//=============================================================//
	//======================== VALIDATOR =========================//
	//=============================================================//

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kolom_1', 'kolom_1', 'required|trim');
		$this->form_validation->set_rules('kolom_2', 'kolom_2', 'required|trim');

		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Please check your input again!',
				'errors' => $this->form_validation->error_array()
			]);
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
		//=========================================================//

		$config['upload_path'] = './uploads/apa/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload("foto"))
			response([
				'status' => false,
				'message' => $this->upload->display_errors()
			], 404);

		$this->db->trans_begin();	// Begin transaction

		$insert_id = $this->Crud->insert(
			[
				'uuid' => uuid(),
				'kolom_1' => post('kolom_1'),
				'kolom_2' => post('kolom_2'),
				'foto' => $this->upload->data('file_name'),
				'is_active' => '1',
				'created_at' => now(),
				'created_by' => get_user_id(),
			]
		);

		if (post('id_sesuatu_a[]')) {
			foreach (post('id_sesuatu_a[]') as $k => $v) {
				$this->db->insert('ini_has_sesuatu', [
					'uuid' => uuid(),
					'id_ini' => $insert_id,
					'id_sesuatu' => $v,
					'is_active' => '1',
					'created_at' => now(),
					'created_by' => get_user_id()
				]);
			}
		}

		$insert_batch = null;
		foreach ($_FILES['files']['name'] as $k => $v) {
			$_FILES['file']['name'] = $_FILES['files']['name'][$k];
			$_FILES['file']['type'] = $_FILES['files']['type'][$k];
			$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$k];
			$_FILES['file']['error'] = $_FILES['files']['error'][$k];
			$_FILES['file']['size'] = $_FILES['files']['size'][$k];

			// Upload file to server
			if ($this->upload->do_upload('file')) {
				// Uploaded file data
				$file_data = $this->upload->data();

				$insert_batch[$k]['id_ini'] = $insert_id;
				$insert_batch[$k]['image'] = $file_data['file_name'];
				$insert_batch[$k]['is_active'] = '1';
				$insert_batch[$k]['created_at'] = date("Y-m-d H:i:s");
				$insert_batch[$k]['created_by'] = get_user_id();
			}
		}

		if ($insert_batch) $this->db->insert_batch('ini_has_image', $insert_batch);

		//=========================================================//

		if (!$this->db->trans_status()) {	// Check transaction status
			$this->db->trans_rollback();	// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Created successfuly'
		]);
	}

	/**
	 * Keperluan CRUD detail data
	 *
	 */
	public function get_where()
	{
		method('post');
		//=========================================================//

		response([
			'status' => true,
			'message' => 'Found',
			'data' => $this->Crud->get_where(
				[
					'a.id' => post('id'),
					'a.is_active' => '1'
				]
			)
		]);
	}

	/**
	 * Keperluan CRUD ubah data
	 *
	 */
	public function update()
	{
		has_permission("update-{$this->_name}");
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
			if (file_exists("./uploads/apa/" . post('old_foto'))) {
				unlink("./uploads/apa/" . post('old_foto'));
			}

			if (!$this->upload->do_upload("foto")) {
				response([
					'status' => false,
					'message' => $this->upload->display_errors()
				], 404);
			}
		}

		$this->db->trans_begin();	// Begin transaction

		$this->Crud->update(
			[
				'uuid' => uuid(),
				'kolom_1' => post('kolom_1'),
				'kolom_2' => post('kolom_2'),
				'foto' => $_FILES['foto']['error'] === 4
					? post('old_foto') : $this->upload->data('file_name'),
				'is_active' => '1',
				'updated_at' => now(),
				'updated_by' => get_user_id(),
			],
			[
				'id' => post('id')
			]
		);

		if (post('id_sesuatu_a[]')) {

			$this->db->update('ini_has_sesuatu', [
				'is_active' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			], [
				'id_ini' => post('id'),
			]);

			foreach (post('id_sesuatu_a[]') as $k => $v) {
				$this->db->insert('ini_has_sesuatu', [
					'uuid' => uuid(),
					'id_ini' => post('id'),
					'id_sesuatu' => $v,
					'is_active' => '1',
					'created_at' => now(),
					'created_by' => get_user_id()
				]);
			}
		}

		// Files will remove (jika ada foto yang akan dihapus)
		if (post('id_images_will_remove[]')) {
			foreach (post('id_images_will_remove[]') as $k => $v) {

				$this->db->update('ini_has_image', [
					'is_active' => '0',
					'deleted_at' => date("Y-m-d H:i:s"),
					'deleted_by' => get_user_id()
				], [
					'id' => $v
				]);

				// Hapus Image
				$this->db->select('image');
				$this->db->from('ini_has_image');
				$this->db->where([
					'is_active' => '0',
					'id' => $v
				]);
				$image = @$this->db->get()->row()->image;

				if ($image) {
					if (file_exists("./uploads/apa/{$image}")) {
						unlink("./uploads/apa/{$image}");
					}
				}
			}
		}

		// Insert image baru
		$insert_batch = null;
		foreach ($_FILES['files']['name'] as $k => $v) {
			$_FILES['file']['name'] = $_FILES['files']['name'][$k];
			$_FILES['file']['type'] = $_FILES['files']['type'][$k];
			$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$k];
			$_FILES['file']['error'] = $_FILES['files']['error'][$k];
			$_FILES['file']['size'] = $_FILES['files']['size'][$k];

			// Upload file to server
			if ($this->upload->do_upload('file')) {
				// Uploaded file data
				$file_data = $this->upload->data();

				$insert_batch[$k]['id_ini'] = post('id');
				$insert_batch[$k]['image'] = $file_data['file_name'];
				$insert_batch[$k]['is_active'] = '1';
				$insert_batch[$k]['created_at'] = date("Y-m-d H:i:s");
				$insert_batch[$k]['created_by'] = get_user_id();
			}
		}

		if ($insert_batch) $this->db->insert_batch('ini_has_image', $insert_batch);

		//=========================================================//

		if (!$this->db->trans_status()) {	// Check transaction status
			$this->db->trans_rollback();	// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Updated successfuly'
		]);
	}

	/**
	 * Keperluan CRUD hapus data
	 *
	 */
	public function delete()
	{
		has_permission("delete-{$this->_name}");
		method('post');
		//=========================================================//

		$data = $this->Crud->get_where([
			'a.id' => post('id', true),
			'a.is_active' => '1'
		]);

		if (file_exists("./uploads/apa/{$data->foto}")) {
			unlink("./uploads/apa/{$data->foto}");
		}

		$this->db->trans_begin();	// Begin transaction
		$this->Crud->update(
			[
				'is_active' => '0',
				'deleted_at' => now(),
				'deleted_by' => get_user_id()
			],
			[
				'id' => post('id')
			]
		);

		//=========================================================//

		if (!$this->db->trans_status()) {	// Check transaction status
			$this->db->trans_rollback();	// Rollback transaction
			response([
				'status' => false,
				'message' => 'Failed',
				'errors' => $this->db->error()
			], 500);
		}

		$this->db->trans_commit();		// Commit transaction

		response([
			'status' => true,
			'message' => 'Deleted successfuly',
		]);
	}

	/**
	 * Keperluan export Excel
	 *
	 * @return void
	 */
	public function export_excel(): void
	{
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Data Apa');
		$spreadsheet->getProperties()->setCreator('Siapa')
			->setLastModifiedBy('Siapa')
			->setTitle('Data Apa')
			->setSubject('Data Apa')
			->setDescription('Data Apa')
			->setKeywords('data apa');

		$spreadsheet->getActiveSheet()->getStyle('B5:G5')
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('FFFA65');

		$spreadsheet->getActiveSheet(0)
			->setCellValue('F2', 'DATA APA')
			->getStyle('F2')
			->getFont()->setBold(true);

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('B5', '#')
			->getStyle('B5')
			->getFont()->setBold(true);
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('C5', 'KOLOM 1')
			->getStyle('C5')
			->getFont()
			->setBold(true);
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('D5', 'KOLOM 2')
			->getStyle('D5')
			->getFont()
			->setBold(true);
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('E5', 'KOLOM 3')
			->getStyle('E5')
			->getFont()
			->setBold(true);
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('F5', 'KOLOM 4')
			->getStyle('F5')
			->getFont()
			->setBold(true);
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('G5', 'KOLOM 5')
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
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $awal, $datum->kolom_1);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $awal, $datum->kolom_2);
			$spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit('E' . $awal, "{$datum->kolom_3}", DataType::TYPE_STRING);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $awal, $datum->kolom_4);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $awal, $datum->kolom_5);

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
		header('Content-Disposition: attachment;filename="data_apa.xlsx"');
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
				response([
					'status' => false,
					'message' => 'Ekstensi file tidak sesuai! Wajib <b>.xlsx</b>',
					'mimetype' => mime_content_type($_FILES['import_file_excel']['tmp_name'])
				], 404);
			}

			$spreadsheet = IOFactory::load($_FILES['import_file_excel']['tmp_name']);
			$data = $spreadsheet->getActiveSheet()->toArray();

			if (
				!($data[3][1] === 'NO' && $data[3][2] === 'KOLOM 1' && $data[3][3] === 'KOLOM 2' && $data[3][4] === 'KOLOM 3'
					&& $data[3][5] === 'KOLOM 4' && $data[3][6] === 'KOLOM 5' && $data[3][7] === 'KOLOM 6' && $data[3][8] === 'KOLOM 7')
			) {
				response([
					'status' => false,
					'message' => 'Format tidak sesuai! mohon disesuaikan dengan template',
				], 404);
			}

			for ($i = 4; $i < count($data); $i++) {
				if (
					$data[$i][1] && $data[$i][1] !== 'NO' && $data[$i][2] && $data[$i][2] !== 'KOLOM 1'
					&& $data[$i][3] !== 'KOLOM 2' && $data[$i][4] !== 'KOLOM 3' && $data[$i][5] !== 'KOLOM 4'
					&& $data[$i][6] !== 'KOLOM 5' && $data[$i][7] !== 'KOLOM 6' && $data[$i][8] !== 'KOLOM 7'
				) {
					$this->Crud->insert(
						[
							'uuid' => uuid(),
							'kolom_1' => $data[$i][2] ?? null,
							'kolom_2' => $data[$i][3] ?? null,
							'kolom_b_id' => $this->db->get_where('b', ['nama' => strtoupper($data[$i][5])])->row()->id ?? null,
							'kolom_c_id' => $this->db->get_where('c', ['nama' => strtoupper($data[$i][6])])->row()->id ?? null,
							'kolom_3' => $data[$i][4] ?? null,
							'kolom_4' => $data[$i][7] ?? null,
							'kolom_5' =>  $data[$i][8] ?? null,
							'kolom_6' => null,
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
			'message' => 'Gagal melakukan import! Ada kesalahan'
		], 404);
	}

	/**
	 * Keperluan download template excel
	 * 
	 * @return void
	 */
	public function download_template_excel()
	{
		$spreadsheet = IOFactory::load(FCPATH . 'assets/templates/excel/template_excel.xlsx');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="template_excel.xlsx"');
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
		header("Content-Disposition: attachment; filename=data_apa.docx");
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
		$dompdf->stream('data_apa.pdf');
	}
}

/* End of file NamaController.php */
