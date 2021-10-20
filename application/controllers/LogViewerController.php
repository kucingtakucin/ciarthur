<?php

class LogViewerController extends MY_Controller
{
    private $logViewer;

    public function __construct()
    {
        parent::__construct();
        role('admin');
        $this->logViewer = new \CILogViewer\CILogViewer();
        //...
    }

    public function index()
    {
        echo $this->logViewer->showLogs();
        return;
    }
}
