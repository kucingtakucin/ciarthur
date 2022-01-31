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
		$this->encryption->initialize(
			[
				'driver' => 'openssl',
				'cipher' => 'aes-256',
				'mode' => 'ctr',
			]
		);
	}
}
