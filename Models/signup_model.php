<?php

use PHPMailer\PHPMailer\PHPMailer;

class Signup_Model extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    public function createUser(array $dataArray)
    {
        $query = "INSERT INTO users (Username, Password, Mail, Role, Date_of_birth)
                  VALUES (?, ?, ?, ?, ?);";
        $parameters = array($dataArray['username'], $dataArray['password'], $dataArray['email'], 0 ,$dataArray['bday']);
        return parent::ExecuteEditQuery($query, 'sssis', $parameters);
    }
    public function emailExists(string $email)
    {
        return parent::ExecuteSelectQuery("SELECT Mail FROM users WHERE Mail = ?", "s", array($email))->num_rows > 0;
    }
    public function userExists(string $username)
    {
        return parent::ExecuteSelectQuery("SELECT Username FROM users WHERE Username = ?", "s", array($username))->num_rows > 0;
    }
    public function verifyEmail(string $email, string $vKey)
    {
        return parent::ExecuteEditQuery("UPDATE users SET VerifiedMail = ?, Mail = null  WHERE Mail = ? AND VerifyKey = ?", 'sss', array($email, $email, $vKey));
    }
    
    public function sendVerification($email)
    {
        // random hashed key is to be send to db and recipient mail
        $verifyKey = password_hash(rand(1000, 5000), PASSWORD_BCRYPT);

        $query = "UPDATE users SET VerifyKey = ? WHERE Mail = ?";
        $parameters = array($verifyKey, $email);
        if(parent::ExecuteEditQuery($query, 'ss', $parameters))
        {
            // if the verificationkey has been inserted in the db, we send a mail 
            $subject = 'Verify your email!';
            $message = 'Thank you for signing up for WordLearner, as the dutch would say: "Snap niet waarom je dit zou willen proberen."


                        Please Verify your email by clicking on this link:  
                            
                        
'. URL . 'signup/verify?email='. $email . '&vKey=' . $verifyKey;

           
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
            return true;
        }
        else
        {
            return false;
        }
    }

}