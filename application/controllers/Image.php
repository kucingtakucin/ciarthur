
<?php
use League\Flysystem\Local\LocalFilesytemAdapter as Local;
use League\Flysystem\Filesystem;
use League\Glide\ServerFactory;


defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller {

    public function show(...$path)
    {
        // Setup Glide server
        $server = ServerFactory::create([
            'source' => new Filesystem(new Local(APPPATH . '../uploads')),
            'cache' => new Filesystem(new Local(APPPATH .'../uploads/.cache')),        
        ]);

        $image_path = implode('/', $path);

        // But, a better approach is to use information from the request
        return $server->outputImage($image_path, $this->input->get());
    }
}