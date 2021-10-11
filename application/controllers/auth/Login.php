<?php

use ReCaptcha\ReCaptcha;


defined('BASEPATH') or exit('No direct script access allowed');


class Login extends MY_Controller
{
    private $_path = 'auth/login/'; // Contoh 'backend/admin/dashboard'

    /**
     * Log the user in
     */
    public function index()
    {
        $this->data['title'] = $this->lang->line('login_heading');
        $recaptcha = new ReCaptcha('6LdJtNgbAAAAALWNC1uQKmM0TLpE9zY0uaSil-_o');
        $response = $recaptcha->setExpectedHostname('appt.demoo.id')
            ->verify($this->input->post('g-recaptcha-response'));

        if ($this->input->method() == 'get') {
            if (logged_in()) {
                redirect('backend/dashboard');
            }

            // the user is not logging in so display the login page
            $this->templates->render([
                'title' => 'Login',
                'type' => 'auth',
                'uri_segment' => $this->_path,
                'page' => $this->_path . 'login',
                'script' => $this->_path . 'js/script_js',
                'style' => $this->_path . 'css/style_css',
                'modal' => [],
            ]);
        } elseif ($this->input->method() == 'post' && $response->isSuccess()) {
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
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                return $this->output->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => $this->ion_auth->errors()
                    ]));
            }
        } else {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Ada kesalahan. Silakan coba lagi',
                    'errors' => $response->getErrorCodes()
                ]));
        }
    }

    /**
     * 
     * Log the user out
     */
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        redirect('auth/login', 'refresh');
    }
}
