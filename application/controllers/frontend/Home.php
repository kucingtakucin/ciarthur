<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    private $_path = 'frontend/home/';
    private $_table = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->templates->render([
            'title' => 'Home',
            'type' => 'frontend',
            'uri_segment' => $this->_path,
            'page' => 'contents/' . $this->_path . 'index',
            'script' => 'contents/' . $this->_path . 'js/script_js',
            'style' => 'contents/' . $this->_path . 'css/style_css',
            'modals' => []
        ]);
    }
}
