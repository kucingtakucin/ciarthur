<?php
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Glide\ServerFactory;


defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller {

    public function show(...$path)
    {
        // Setup Glide server
        $server = ServerFactory::create([
            'source' => new Filesystem(new LocalFilesystemAdapter(FCPATH . 'uploads')),
            'cache' => new Filesystem(new LocalFilesystemAdapter(FCPATH .'uploads/.cache')),        
        ]);

        $image_path = implode('/', $path);
        $server->outputImage($image_path, $this->input->get());
    }
}