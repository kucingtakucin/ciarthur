<?php


defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	/**
	 * MY_Controller constructor
	 */
	public function __construct()
	{
		parent::__construct();

		header('Access-Control-Allow-Origin: ' . base_url());
		header('Access-Control-Allow-Headers: *');
		header('Access-Control-Expose-Headers: *');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');

		session('csrf', csrf());
		$this->encryption->initialize(
			[
				'driver' => 'openssl',
				'cipher' => 'aes-256',
				'mode' => 'ctr',
			]
		);
	}
}
