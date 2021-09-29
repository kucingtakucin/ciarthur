<?php

use chriskacerguis\RestServer\RestController;

class Auth extends RestController
{
    public function __construct()
    {
        parent::__construct();

        // Load the user model
        $this->load->model('ion_auth_model', 'auth');
    }

    public function login_post()
    {
        // Get the post data
        $username = $this->post('username');
        $password = $this->post('password');

        // Validate the post data
        if (!empty($username) && !empty($password)) {

            if ($this->auth->login($this->input->post('identity'), $this->input->post('password'))) {
                //if the login is successful
                //redirect them back to the home page
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Login successfuly',
                    'api_key' => generate_api_key(),
                    'api_key2' => md5('administratr:REST API:password')
                ], RestController::HTTP_OK);
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->response("Wrong username or password", RestController::HTTP_BAD_REQUEST);
            }
        } else {
            // Set the response and exit
            $this->response("Provide username and password", RestController::HTTP_BAD_REQUEST);
        }
    }
}
