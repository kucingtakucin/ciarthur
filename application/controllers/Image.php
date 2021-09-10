
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller {

    public function show($path = null)
    {
        // Setup Glide server
        $server = League\Glide\ServerFactory::create([
            'source' => APPPATH . '../uploads',
            'cache' => APPPATH .'../uploads/.cache',        
        ]);

        // But, a better approach is to use information from the request
        $server->outputImage($path, $this->input->get());
    }
}