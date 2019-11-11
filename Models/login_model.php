<?php
use PHPMailer\PHPMailer\PHPMailer;
class Login_Model extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check(string $username, string $password)
    {
        $query = "SELECT * FROM users WHERE Username = ?";
        $parameters = array($username);
        $result = parent::ExecuteSelectQuery($query, 's', $parameters);

        if(!$result)
        {
            // Something went wrong, connection error or the end of the world
            header('location:' . URL . 'login?error=failure');   
            return; 
        }   

        if($result->num_rows == 1)
        {  
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password']))
            {
                Session::set('UserID', $row['UserID']);
                Session::set('Username', $row['Username']);
                Session::set('Mail', $row['Mail']);
                Session::set('Role', $row['Role']);
                
                //succes, back to home you go, maybe in php2 see if you can have him return on the page he logged in from
                header('location: ' . URL);
            }
            else
            {
                // incorrect credentials
                header('location:' . URL . 'login?error=norows');
                exit;
            }
        }
        else
        { 
            // incorrect credentials
            header('location:' . URL . 'login?error=norows');
            exit;
        }
    }
    public function setRecoveryToken(string $email, $key) // $key must be able to take a null, so no type hinting
    {
        if(!is_null($key)) {
            // db gets encrypted key
            $key = password_hash($key, PASSWORD_DEFAULT);
        }
        return parent::ExecuteEditQuery("UPDATE users SET RecoverKey = ? WHERE VerifiedMail = ?", 'ss', array($key, $email));
    }
    public function getRecoveryTokenbyID(int $id)
    {
        return parent::ExecuteSelectQuery("SELECT RecoverKey FROM users WHERE UserID = ?", 'i', array($id))->fetch_assoc()['RecoverKey'];
    }
    public function getUserIDbyEmail(string $email)
    {
        return parent::ExecuteSelectQuery("SELECT UserID FROM users WHERE VerifiedMail = ?", 's', array($email))->fetch_assoc()['UserID'];
    }
    public function emailVerified(string $email)
    {
        return parent::ExecuteSelectQuery("SELECT VerifiedMail FROM users WHERE VerifiedMail = ?", 's', array($email))->num_rows > 0;
    }
    public function getEmailByID(int $id)
    {
        return parent::ExecuteSelectQuery("SELECT VerifiedMail FROM users WHERE UserID = ?", 'i', array($id))->fetch_assoc()['VerifiedMail'];
    }
    public function changePasswordbyID(string $password, int $id)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        return parent::ExecuteEditQuery("UPDATE users SET Password = ? WHERE UserID = ?", 'si', array($password, $id));
    }
    public function sendRecoveryMail(string $email, string $key)
    {
        // mail gets unencrypted key of course
        $subject = 'Reset your password with this link';
        $message = 'Hello! 
        
To reset your password at WordLearner,
We need you to follow this link:

                    
' . URL . 'login/reset?token='. $key . ' 



If you did not request this password recovery, you can ignore this email.';

        // Mail::send($email, $subject, $message);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = MAIL_SERVER;
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = MAIL_USERNAME;                      // SMTP username
        $mail->Password   = MAIL_PASSWORD;                      // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USERNAME, 'WordLearner');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $mail->send();
    }

}
