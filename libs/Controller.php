<?php 

class Controller 
{
    
    function __construct()
    {
        // every controller gets a view instantiated by default, with it, I can pass relevant variables to the page that the user gets to see
        $this->view = new View();
        // initialize the session whenever a page (which refers to a controller) loads
        Session::init();
    }

    function loadModel(string $name)
    {
        // The url is (usually) also a model which handles the logic, so the instantiated controller will attempt to load it with this function 
        $path = 'Models/' . $name . '_model.php';
        if(file_exists($path))
        {
            require $path;
            // I name most of my models with a '_Model' ending, I instantiate the given model as soon as I add that part to it
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }
}
