<?php 

class Errorhandle extends Controller 
{
    public function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        // Any page that does not exists refers to this function, which renders an error view
        $this->view->render('error/index');
    }
}
