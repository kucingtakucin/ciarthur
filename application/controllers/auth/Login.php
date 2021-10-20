<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Login extends MY_Controller
{
    private $_path = 'auth/login/'; // Contoh 'backend/admin/dashboard'


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Log the user in
     */
    public function index()
    {
        role('guest');
        if ($this->input->method() === 'get') {
            // the user is not logging in so display the login page
            return $this->templates->render([
                'title' => 'Login',
                'type' => 'auth',
                'uri_segment' => $this->_path,
                'page' => $this->_path . 'login',
                'script' => $this->_path . 'js/script_js',
                'style' => $this->_path . 'css/style_css',
                'modal' => [],
            ]);
        } elseif ($this->input->method() === 'post') {

            $response = $this->recaptcha->is_valid(
                $this->input->post('g-recaptcha-response'),
                $this->input->ip_address()
            );

            $this->_validator();

            if (!$this->input->post('g-recaptcha-response')) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(422)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => "Recaptcha wajib dicentang!",
                        'data' => null
                    ]));
            }

            if (!$response['success'] && $response['error']) {
                return $this->output->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode([
                        'status' => false,
                        'title' => 'Recaptcha error',
                        'errors' => $response['error_message'],
                    ]));
            }
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                //redirect them back to the home page
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Login Berhasil!',
                        'redirect' => 'backend/dashboard'
                    ]));
            }
            // if the login was un-successful
            // redirect them back to the login page
            return $this->output->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $this->ion_auth->errors()
                ]));
        }
    }

    /**
     * Keperluan validasi server-side
     */
    private function _validator()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('identity', 'username', 'required|trim');
        $this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]');
        if (!$this->form_validation->run()) {
            $this->output->set_content_type('application/json')
                ->set_status_header(422);
            echo json_encode([
                'status' => false,
                'message' => 'Incorrect Login!',
                'errors' => $this->form_validation->error_array()
            ]);
            exit;
        }
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
