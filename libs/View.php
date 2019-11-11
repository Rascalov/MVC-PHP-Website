<?php 

class View  
{
    
    function __construct()
    { 
    }
    public function render(string $name, bool $useHeaderAndFooter = true)
    {
        // Check if the desired page needs to load in the header and footer, might be of use for pages made for PHP 2 
        switch ($useHeaderAndFooter) 
        {
            case true:
                require 'Views/header.php';
                require 'Views/' . $name . '.php';
                require 'Views/footer.php';
            break;

            case false:
                require 'Views/' . $name . '.php';
            break;
        }
    }
}
