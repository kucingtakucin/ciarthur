<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Templates
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function load($data)
    {
        $this->ci->load->view("templates/{$data['type']}/app", $data);
    }
}

/* End of file LibraryName.php */
