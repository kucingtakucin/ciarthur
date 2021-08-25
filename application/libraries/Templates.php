<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Templates
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * Render template
     *
     * @param array $data
     * @return CI_Loader
     */
    public function render(array $data): CI_Loader
    {
        return $this->ci->load->view("templates/{$data['type']}/app", $data);
    }
}

/* End of file LibraryName.php */
