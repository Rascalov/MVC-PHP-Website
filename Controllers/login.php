<?php 

class Login extends Controller 
{
    function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        // If already logged in, head back to the homepage
        if(Session::exists())
        {
            header('location:' . URL);
            exit;
        }
        // render login view
        $this->view->render('login/index');
    }
    function check()
    {
        // prevent user from accessing this page if no info entered
        if(!isset($_POST['Loginuname']) || !isset($_POST['Loginpsw']))
        {
            header('location:'. URL.'error');
            return;
        }

        if($_POST['LoginCaptcha'] != Session::get('captcha_string') )
        {
            header('location:' . URL . 'login?error=invalidcaptcha');
            exit;
        }

        $username = $_POST['Loginuname'];
        $password = $_POST['Loginpsw'];
        // Check if the user exists
        $this->model->check($username, $password);
    }
    function forgot()
    {
        $this->view->render('login/forgot');
    }
    function forgotCheck()
    {
        // checking input and sending the mail (with success message)

        // checks if it's set
        if(!isset($_POST['ForgotEmail']))
        {
            header('location:' . URL . 'error' );
        }
        else
        {
            // random hashed key (will be hashed again later for the db)
            $mail = htmlspecialchars($_POST['ForgotEmail']);
            if($this->model->emailVerified($mail))
            {
                $key = password_hash(rand(100000, 800000), PASSWORD_DEFAULT);
                if ($this->model->setRecoveryToken($mail, $key)) {
                    $key = strval($this->model->getUserIDbyEmail($mail)) . '$' .  $key;
                    $this->model->sendRecoveryMail($mail, $key);
                    header('location:' . URL . 'login/forgot?status=success');
                } else {
                    header('location:' . URL . 'login/forgot?error=failure');
                }
            }
            else
            {
                header('location:' . URL . 'login/forgot');
            }
        }

        // db has a RecoverKey (our token) encrypted in the db (created as soon as the email is given), but first given by mail as unencrypted 
        // this token (link + with GET as token)
    }
    function reset()
    {
        // no token, no access 
        if (!isset($_GET['token'])) {
            header('location:' . URL . 'error');
        }
        $token = $_GET['token'];
        try
        {
            $data = explode('$', $token, 2);
            // check if token is genuine
            $hashedtoken = $this->model->getRecoveryTokenbyID($data[0]);
            if (password_verify($data[1], $hashedtoken)) 
            {
                // give the userid to the reset page to place in hidden field
                $this->view->UserID = $data[0]; 
                $this->view->render('login/reset');
            }
            else
            {
                header('location:' . URL . 'error');
            }
        }
        catch (Exception $e)
        {
            header('location:' . URL . 'error');
        }
        
        
    }
    function resetCheck()
    {
       
        // no password
        if (!isset($_POST['ResetPsw']) || !isset($_POST['ResetPsw-repeat'])) {
            header('location:' . URL . 'error');
        }
        if ($_POST['ResetPsw'] != $_POST['ResetPsw-repeat']) {
            header('location:' . URL . 'login/reset?error=pswnotequal');
        }        
        $password = htmlspecialchars($_POST['ResetPsw']);
        // we got a id from the hidden token field
        $id = $_POST['UserID'];
        //previous function already validated the request, so now we change the password
        if($this->model->changePasswordbyID($password, (int)$id))
        {
            // at the end of all, remove the token from the db by setting it to null
            $email =  $this->model->getEmailByID($id);
            $this->model->setRecoveryToken($email, null);
            header('location:' . URL . 'login?error=pswchange');
        } 
        else 
        {
            header('location:' . URL . 'login/reset?error=failure');
        }    
    }
    function logout()
    {
        Session::destroy();
        header('location:' . URL);
    }

}
