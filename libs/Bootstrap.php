<?php
class Bootstrap
{
    function __construct()
    {
        // Disclaimer: with 'url' I mean everything that comes after the sites location ( eg. https://www.iets.nl/{URL} )
        // Get the url if it's set, else null
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        // trim slashes on the right of the url
        $url = rtrim($url, '/');
        // clean url 
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // split them up per slash
        $url = explode('/', $url);

        // no url? Just send them to the index page
        if(empty($url[0]))
        {
            require 'Controllers/index.php';
            $controller = new Index();
            $controller->index();
            return;
        }

        // Check if the url can translate to a existing Controller file
        $file = 'Controllers/' . $url[0] . '.php';
        if(file_exists($file))
        {
            require $file;
        }
        else
        {
            return $this->error();
        }
        // the controller classes have the same name, so we can use the url to instantiate the relevant controller for the page
        $controller = new $url[0];
        // every controller has a model, with the same name (+ a little extension: '_model'), here we call main controller function to load relevant model 
        $controller->loadModel($url[0]);

        // should our url be bigger, for example: site.com/url/url2/url3
        if(isset($url[2]))
        {
            // check if a method of that name exists within the class 
            if(method_exists($controller, $url[1]))
            {
                // call the function that corresponds with the url
                $controller->{$url[1]}($url[2]);
            }
            else
            {
                return $this->error();
            }
        }
        else
        {
            // check for url like: site.com/url/url2
            if (isset($url[1]))
            {
                // check if a method with the url2 name exists within the class
                if(method_exists($controller, $url[1]))
                {
                    // call that method
                    $controller->{$url[1]}();
                }
                else
                {
                    return $this->error();
                }
            }
            else
            {
                // last else is just url[0], which means the controller class, which we'll just direct to his index
                $controller->index();
            }
        }
    }
    // On non existing page, refer to the error page
    function error()
    {
        require 'Controllers/errorhandle.php';
        $controller = new Errorhandle();
        $controller->index();
        return;
    }
}
