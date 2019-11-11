<?php 

class Users extends Controller 
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // login check
        if (!Session::exists()) {
            header('location:' . URL . 'login');
        }
        if(isset($_GET['searchquery']))
        {
            // cap
            if ($_GET['Captcha'] != Session::get('captcha_string')) {
                $this->view->userlist = null;
            }
            else
            {
                $this->view->userlist = $this->model->SearchUsers(htmlspecialchars($_GET['searchquery']));
            }
        }
        else
        {
            $this->view->userlist = null;
        }
        $this->view->render('users/index');
    }
    public function user()
    {
        if (!Session::exists()) {
            header('location:' . URL . 'login');
            exit;
        }
        if(!isset($_GET['name']))
        {
            header('location:' . URL . 'error');
            exit;
        }
        $this->view->user = $this->model->getUserByName($_GET['name']);
        if($this->view->user === null)
        {
            header('location:' . URL . 'error');
            exit;
        }
        
        $this->view->CanEdit = ($this->view->user->Username == Session::get('Username') 
                                || Session::get('Role') == Role::Admin 
                                || Session::get('Role') == Role::SuperAdmin) ? true : false;
        $this->view->render('users/user');
        // give to the view a variable that either gives them permission to edit or not. 

        // Given the username as GET, It will show their information, 
        
        // if YOU are that user OR an admin, you can change details (probably checks at view for role)
        // Admin can also see a 'log in as this user' button, to access the account. (view)
        

    }
    public function update()
    {
        // so here we check first what the user has changed by getting the user with his (original if edited) username
        $user = $this->model->getUserByName($_POST['User-identifier']);
        
        // We update everything in one go, which is not that great 
        // because if one task fails for whatever reason, we can't rewind the others or display a seperate error for it. 
        // but it'll do for this assignment
        $pswChanged = false; $userChanged = false; $emailChanged = false;
        // we first check if user inserted a new password
        $username = htmlspecialchars($_POST['User-Username']);
        $email = htmlspecialchars($_POST['User-Email']);

        if($_POST['User-psw'] != "")
        {
            $psw = $_POST['User-psw'];
            $pswrepeat = $_POST['User-psw-repeat'];
            if($psw != $pswrepeat)
            {
                header('location:' . URL . 'users/user?name='. $user->Username . '&error=pswnotequal');
                exit;
            }
            if($this->model->updatePasswordByUserID($user->UserID, $psw))
            {
                $pswChanged = true;
            }
            else
            {
                header('location:' . URL . 'users/user?name=' . $user->Username . '&error=failure');
                exit;
            }
        }
        // then email (check if mail exist)
        if($user->GetCorrectEmail() != $email)
        {
            if($this->model->emailExists($email))
            {
                header('location:' . URL . 'users/user?name=' . $user->Username . '&error=emailexists');
                exit;
            }
            if($this->model->updateEmailByUserID($user->UserID, $email))
            {
                $emailChanged = true;
            }
            else
            {
                header('location:' . URL . 'users/user?name=' . $user->Username . '&error=failure');
                exit;
            }
        }

        
        // then username (check if username already taken)
        if ($user->Username != $username)
        {
            // echo $user->Username . '<br>';
            // echo $username;

            if($this->model->userExists($username))
            {
                header('location:' . URL . 'users/user?name=' . $user->Username . '&error=userexists');
                exit;
            }
            if($this->model->updateUsernameByUserID($user->UserID, $username))
            {
                $userChanged = true;
                // If user is changing his own username, his session variable needs to change as well
                if(Session::get('Username') == $_POST['User-identifier']){
                    Session::set('Username', $username);
                }
                $user->Username = $username;
            }
            else
            {
                header('location:' . URL . 'users/user?name=' . $user->Username . '&error=failure');
                exit;
            }
        }
        // mail all changes made to the user (if user made any)
        if($pswChanged || $userChanged || $emailChanged)
        {
            $this->model->MailUserUpdates($user, $pswChanged, $userChanged, $emailChanged);
        }

        // if mail is changed, it has to get verified, otherwise the original mail will still be the verified mail
        if($emailChanged)
        {
            header('location:' . URL . 'signup/verify?email=' . $email);
        }
        else
        {
            // for all other changes, we'll just display a message on the same page
            header('location:' . URL . 'users/user?name=' . $user->Username . '&error=updated');
        }
    }

    public function takeover()
    {
        $user = $this->model->getUserByName($_POST['User-identifier']);
        // To log in to someone else's account, just change your session variables to the user's
        Session::set('Username', $user->Username);
        Session::set('Mail', $user->GetCorrectEmail());
        Session::set('UserID', $user->UserID);
        Session::set('Role', $user->Role);
        // and refer user out to the home page
        header('location:' . URL);
    }
    public function delete()
    {
        $user = $this->model->getUserByName($_POST['User-identifier']);
        if($this->model->deleteUserByUserID($user->UserID))
        {
            header('location:'. URL . 'users');
        }
        else
        {
            header('location:' . URL . 'users/user?name=' . $user->Username . '&error=failure');
        }
    }
    public function create()
    {
        if(Session::get('Role') != Role::SuperAdmin)
        {
            header('location:' . URL . 'error');
            exit;
        }
        $this->view->render('users/create');
    }
    public function createUser()
    {
        // Check passwords
        if ($_POST['SignupPsw'] != $_POST['SignupPsw-repeat']) {
            header('location:' . URL . 'users/create?error=pswnotequal');
            return;
        }
        if ($this->model->emailExists($_POST['SignupEmail'])) {
            header('location:' . URL . 'users/create?error=emailexists');
            return;
        }
        if ($this->model->userExists($_POST['SignupUsername'])) {
            header('location:' . URL . 'users/create?error=userexists');
            return;
        }
        $array = array(
            'username' => htmlspecialchars($_POST['SignupUsername']),
            'email' => $_POST['SignupEmail'],
            'password' => password_hash($_POST['SignupPsw'], PASSWORD_DEFAULT),
            'bday' =>  htmlspecialchars($_POST['SignupBday']),
            'Role' => $_POST['SignupRole']
        );
        if($this->model->insertUser($array))
        {
            header('location:' . URL . 'users/create?error=usercreated');
        }
        else
        {
            header('location:' . URL . 'users/create?error=failure');
        }
        // if ($this->model->createUser($array)) {
        //     // success! Now send the user to the verification page that sends the verification mail
        //     header('location:' . URL . 'signup/verify?email=' . $array['email']);
        // } else {
        //     header('location:' . URL . 'signup?error=failure');
        // }
    }

}
?>