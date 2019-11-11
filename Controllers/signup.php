<?php 

class Signup extends Controller 
{
    function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        $this->view->render('signup/index');
    }
    function createUser()
    {
        // incase they head here through url tinkering
        if(!isset($_POST['SignupUsername']) || !isset($_POST['SignupEmail']) || !isset($_POST['SignupPsw']) ||!isset($_POST['SignupPsw-repeat']) || !isset($_POST['SignupBday']))
        {
            header('location:' . URL . 'error');
            return;
        }

        if ($_POST['Captcha'] != Session::get('captcha_string')) {
            header('location:' . URL . 'signup?error=invalidcaptcha');
            exit;
        }

        // Check passwords
        if($_POST['SignupPsw'] != $_POST['SignupPsw-repeat'] )
        {   
            header('location:' . URL . 'signup?error=pswnotequal');
            return;
        }
        if($this->model->emailExists($_POST['SignupEmail'])) 
        {
            header('location:' . URL . 'signup?error=emailexists');
            return;
        }
        if($this->model->userExists($_POST['SignupUsername']))
        {
            header('location:' . URL . 'signup?error=userexists');
            return;
        }
        $array = array( 'username' => htmlspecialchars($_POST['SignupUsername']) , 
                        'email' => $_POST['SignupEmail'] ,
                        'password' => password_hash($_POST['SignupPsw'], PASSWORD_DEFAULT) ,
                        'bday' =>  htmlspecialchars($_POST['SignupBday']));
        if($this->model->createUser($array))
        {
            // success! Now send the user to the verification page that sends the verification mail
            header('location:' . URL . 'signup/verify?email='. $array['email']);
        }
        else
        {
            header('location:' . URL . 'signup?error=failure');
        }
    }
    function verify()
    {
        // we need two GET's: email and Vkey. 
        // if both empty: piss right off the page
        // if only vkey: piss off ^
        if(!isset($_GET['email']))
        {
            header('location:'. URL . 'error');
            return;
        }
        
        if(!isset($_GET['vKey']))
        {   
            // if only email: send verification, let view display like 'mail has been sent'
            $this->model->sendVerification($_GET['email']);
            // render verify view
            $this->view->render('signup/verify');
            return; 
        }
        // if all matches, give view a variable to see if it's verified.
        $this->view->verifySuccess = $this->model->verifyEmail($_GET['email'], $_GET['vKey']);
        // render verified view
        $this->view->render('signup/verify');
        // current purpose: verify email so you can access the forgot password functions and edit your user information. 
        // verification should work, even when your not logged in, so we only check the url's vKey and email.

    }
}