<?php

use chriskacerguis\RestServer\RestController;

class Auth extends RestController
{
    public function __construct()
    {
        parent::__construct();

        // Load the user model
        $this->load->model('ion_auth_model', 'auth');
        $this->load->helper('jwt');
        $this->load->config('jwt');
        $this->load->library('session');
    }

    public function index_get()
    {
        echo "Codeigniter <b>Rest-Server</b> <i>v3.1</i> <br><br>";
        echo "Login <b>POST</b> <i>/login</i> <br>";
        echo "Me <b>POST</b>  <i>/me</i> <br>";
        echo "Refresh <b>POST</b>  <i>/refresh</i> <br>";
        echo "Logout <b>POST</b>  <i>/logout</i> <br>";
    }

    public function login_post()
    {
        // Get the post data
        $username = $this->post('username');
        $password = $this->post('password');

        // Validate the post data
        if ($username && $password) {

            if ($this->auth->login($this->input->post('username'), $this->input->post('password'))) {
                //if the login is successful
                // Set the response and exit
                return $this->respondWithToken(generateToken([
                    'id' => $this->auth->get_user_id_from_identity($this->input->post('username')),
                    'username' => $this->input->post('username')
                ]));
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->response([
                    'status' => false,
                    'message' => "Wrong username or password"
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            // Set the response and exit
            $this->response(
                [
                    'status' => false,
                    'message' => "Unauthorized"
                ],
                RestController::HTTP_UNAUTHORIZED
            );
        }
    }

    public function me_post()
    {
        $this->_check_authorization(function ($decodedToken) {
            return $this->response([
                'status' => true,
                'id' => $decodedToken->token->data->id,
                'username' => $decodedToken->token->data->username
            ], RestController::HTTP_OK);
        });
    }

    public function refresh_post()
    {
        $this->_check_authorization(function ($decodedToken) {
            $this->respondWithToken(generateToken([
                'id' => $decodedToken->token->data->id,
                'username' => $decodedToken->token->data->username
            ]));
        });
    }

    public function logout_post()
    {
        $this->_check_authorization(function ($decodedToken, $bearer_token) {
            $update = $this->db->update('tokens', [
                'is_active' => '0'
            ], [
                'user_id' => $decodedToken->token->data->id,
                'is_active' => '1'
            ]);

            if ($update) {
                return $this->response([
                    'status' => true,
                    'message' => "Logout Successfuly"
                ], RestController::HTTP_BAD_REQUEST);
            }
        });
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     */
    protected function respondWithToken($token)
    {
        $this->response([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->config->item('exp')
        ], RestController::HTTP_OK);
    }

    private function _check_authorization(Closure $callback)
    {
        if (
            array_key_exists('Authorization', $this->input->request_headers()) &&
            !empty($this->input->request_headers()['Authorization'])
        ) {
            $headers = explode(" ", $this->input->request_headers()["Authorization"]);
            $bearer_token = end($headers);
            $decodedToken = validateTimestamp($bearer_token);

            // return response if token is valid
            if ($decodedToken->status) {
                // $this->set_response($decodedToken, RestController::HTTP_OK);
                if (is_callable($callback)) {
                    $callback($decodedToken, $bearer_token);
                }
            } else {
                return $this->response([
                    'status' => false,
                    'message' => $decodedToken->message,
                    'exception' => @$decodedToken->exception
                ], RestController::HTTP_BAD_REQUEST);
            }
        }

        $this->response([
            'status' => false,
            'message' => "Unauthorized"
        ], RestController::HTTP_UNAUTHORIZED);
    }
}
