<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Config
		$this->_name = 'login';
		$this->_path = "auth/{$this->_name}/"; // Contoh 'backend/dashboard/ / 'frontend/home/'

	}

	/**
	 * Log the user in
	 */
	public function index()
	{
		role('guest');

		if ($this->input->method() === 'get') :
			// the user is not logging in so display the login page
			$config = [
				'title' => ucwords($this->_name),
				'type' => 'auth',
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'login',
				'script' => $this->_path . 'js/script.js.php',
				'style' => $this->_path . 'css/style.css.php',
				'modal' => [],
			];

			render($config);

		elseif ($this->input->method() === 'post') :

			$response = $this->recaptcha->is_valid(
				post('g-recaptcha-response'),
				$this->input->ip_address()
			);

			$this->_validator();

			if (!post('g-recaptcha-response')) {
				response([
					'status' => false,
					'message' => "Recaptcha wajib dicentang!",
					'data' => null,
				], 422);
			}

			if (!@$response['success'] && @$response['error']) {
				response([
					'status' => false,
					'title' => 'Recaptcha error',
					'message' => $response['error_message'],
				], 400);
			}

			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) post('remember');

			if ($this->ion_auth->login(post('identity'), post('password'), $remember)) {
				//if the login is successful
				//redirect them back to the home page
				response([
					'status' => true,
					'message' => 'Login Berhasil!',
					'redirect' => 'backend/dashboard',
				], 200);
			}

			// if the login was un-successful
			// redirect them back to the login page
			response([
				'status' => false,
				'message' => $this->ion_auth->errors(),
			], 422);
		endif;
	}

	/**
	 * Keperluan validasi server-side
	 */
	private function _validator()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data(post());
		$this->form_validation->set_rules('identity', 'username', 'required|trim');
		$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]');
		if (!$this->form_validation->run())
			response([
				'status' => false,
				'message' => 'Incorrect Login!',
				'errors' => $this->form_validation->error_array(),
				'csrf' => csrf()
			], 422);
	}

	/**
	 * 
	 * Log the user out
	 */
	public function logout()
	{
		method('post');
		$this->data['title'] = "Logout";

		// log the user out
		$this->ion_auth->logout();
		// redirect them to the login page
		redirect('~/login', 'refresh');
	}
}
