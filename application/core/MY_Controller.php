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
		$this->load->model('ion_auth_model');   // Load ion_auth model
		$this->load->model('ion_auth_acl_model');
		$this->load->library([
			'session', 'ion_auth', 'ion_auth_acl', 'encryption', 'form_validation',
			'upload', 'recaptcha', 'pusher'

		]);  // Load library session, ion_auth
		$this->encryption->initialize(
			[
				'driver' => 'openssl',
				'cipher' => 'aes-256',
				'mode' => 'ctr',
			]
		);
	}
}
