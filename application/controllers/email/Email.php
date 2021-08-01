<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email extends MY_Controller
{
    private $_path = 'email/';
    private $_from = 'adam.faizal.af6@gmail.com';
    public $to = 'adam.faizal.af6@student.uns.ac.id';

    const API_KEY = 'a57e89b77246272447227a73fe474764';
    const SECRET_KEY = '9fe694fd45f2c2aedd0def21d1199563';
    const SMTP_SERVER = 'tls://in-v3.mailjet.com';
    const PORT = 465; // 25, 587, 465

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function index()
    {
        if ($this->input->method() != 'post') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Method not allowed'
                ]));
        }

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = self::SMTP_SERVER;
        $config['smtp_user'] = self::API_KEY;
        $config['smtp_pass'] = self::SECRET_KEY;
        $config['smtp_port'] = self::PORT;

        $this->email->initialize($config);
        $this->email->from($this->_from, 'VOC');
        $this->email->to($this->to);

        $this->email->subject('Email Test');
        $this->email->set_mailtype('html');
        $this->email->message($this->load->view($this->_path . 'email', [], true));

        $this->email->send(FALSE);

        return $this->output->set_content_type('text/html')
            ->set_status_header(200)
            ->set_output($this->email->print_debugger(['headers', 'subject', 'body']));
    }
}
