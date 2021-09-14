<?php

use ReCaptcha\ReCaptcha;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan extends MY_Controller
{
    private $_path = 'frontend/pengaduan/';
    private $_table = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pusher');
        $this->load->model($this->_path . 'M_Pengaduan');
    }

    public function index()
    {
        $this->templates->render([
            'title' => 'Pengaduan',
            'type' => 'frontend',
            'uri_segment' => $this->_path,
            'header' => 'contents/' . $this->_path . 'header',
            'page' => 'contents/' . $this->_path . 'index',
            'script' => 'contents/' . $this->_path . 'js/script_js',
            'style' => 'contents/' . $this->_path . 'css/style_css',
            'modals' => []
        ]);
    }

    public function insert()
	{
        $recaptcha = new ReCaptcha('6LdJtNgbAAAAALWNC1uQKmM0TLpE9zY0uaSil-_o');
		$response = $recaptcha->setExpectedHostname('appt.demoo.id')
			->verify($this->input->post('g-recaptcha-response'));

        if ($this->input->method() == 'post' && $response->isSuccess()) {            
            $pusher = $this->pusher->get_pusher();            
            $pusher->trigger('kirim-pengaduan-channel', 'kirim-pengaduan-event', [
                'title' => 'Pemberitahuan',
                'message' => 'Ada pengaduan baru yang masuk!'
            ]);
    
            $this->M_Pengaduan->insert(
                [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'message' => $this->input->post('message'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'is_active' => '1'
                ]
            );
    
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Berhasil mengirimkan pengaduan!'
                ]));
        }
	}
}
