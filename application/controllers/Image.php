
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller {

    public function show(...$path)
    {
        // Setup Glide server
        $server = League\Glide\ServerFactory::create([
            'source' => APPPATH . '../uploads',
            'cache' => APPPATH .'../uploads/.cache',        
        ]);

        $image_path = implode('/', $path);

        // But, a better approach is to use information from the request
        return $server->getImageResponse($image_path, $this->input->get());
    }
}