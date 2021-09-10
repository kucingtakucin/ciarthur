
<?php
defined('BASEPATH') or exit('No direct script access allowed');
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\ServerFactory;

class Image extends CI_Controller {

    public function index($path = null)
    {
        // Setup Glide server
        $server = League\Glide\ServerFactory::create([
            'source' => new Filesystem(new Local(realpath(APPPATH . '../uploads'))),
            'cache' => new Filesystem(new Local(realpath(APPPATH .'../uploads/.cache'))),        
        ]);

        // But, a better approach is to use information from the request
        $server->outputImage($path, $this->input->get());
    }
}