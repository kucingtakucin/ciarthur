<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Csrf extends CI_Controller
{
    /**
     * MY_Controller constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['encryption']);  // Load library session, ion_auth
        $this->encryption->initialize(
            [
                'driver' => 'openssl',
                'cipher' => 'aes-256',
                'mode' => 'ctr',
            ]
        );
    }

    /**
     * Keperluan generate csrf
     *
     * @return string
     */
    public function generate()
    {
        if ($this->input->method() == 'post' && ($this->encryption->decrypt($this->input->post('key')) === bin2hex('csrf'))) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]));
        }
        return $this->output->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Key required',
                'input' => $this->input->post()
            ]));
    }

    public function key()
    {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'key' => bin2hex($this->encryption->create_key(16))
            ]));
    }
}
