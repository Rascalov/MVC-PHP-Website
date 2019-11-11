<?php 

class Index extends Controller
{
    public function __construct()
    {   
        parent::__construct();
    }

    function index()
    {
        // Standard homepage render
        $this->view->render('index/index');
    }
}
