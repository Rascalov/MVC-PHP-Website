<?php

use PHPMailer\PHPMailer\PHPMailer;

class Users_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        require 'user.php';
    }
    public function SearchUsers(string $searchString)
    {
        // add the operators for the LIKE in sql
        $searchString = '%'. $searchString . '%';
        $userlist = array();
        $query = "SELECT UserID, Username, Mail, VerifiedMail, Role, Registration_date, Date_of_Birth FROM users WHERE Username LIKE ? 
                  OR Mail LIKE ? OR VerifiedMail LIKE ? OR Registration_date LIKE ?";
        $result = parent::ExecuteSelectQuery($query, 'ssss', array($searchString, $searchString, $searchString, $searchString));
        while($row = $result->fetch_assoc())
        {
            $user = new User($row['UserID'], $row['Username'], $row['Mail'], $row['VerifiedMail'], new DateTime($row['Registration_date']), new DateTime($row['Date_of_Birth']), $row['Role']);
            array_push($userlist, $user);
        }
        return $userlist;
    }
    public function getUserByName(string $username)
    {
        $result = parent::ExecuteSelectQuery("SELECT UserID, Username, Mail, VerifiedMail, Role, Registration_date, Date_of_Birth 
                                              FROM users WHERE Username = ?", 's', array($username));
        if($result->num_rows == 0)
        {
            $user = null;
        }
        else
        {
            $row = $result->fetch_assoc();
            $user = new User($row['UserID'], $row['Username'], $row['Mail'], $row['VerifiedMail'], new DateTime($row['Registration_date']), new DateTime($row['Date_of_Birth']) ,$row['Role']);
        }
        return $user;
    }
    public function updatePasswordByUserID(int $id, string $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        return parent::ExecuteEditQuery("UPDATE users SET Password = ? WHERE UserID = ?", 'si', array($password, $id));
    }
    public function updateEmailByUserID(int $id, string $email)
    {
        return parent::ExecuteEditQuery("UPDATE users SET Mail = ? WHERE UserID = ?", 'si', array($email, $id));
    }
    public function updateUsernameByUserID(int $id, string $username)
    {
        return parent::ExecuteEditQuery("UPDATE users SET Username = ? WHERE UserID = ?", 'si', array($username, $id));
    }
    public function userExists(string $username)
    {
        return parent::ExecuteSelectQuery("SELECT Username FROM users WHERE Username = ?", "s", array($username))->num_rows > 0;
    }
    public function emailExists(string $email)
    {
        return parent::ExecuteSelectQuery("SELECT Mail, VerifiedMail FROM users WHERE Mail = ? OR VerifiedMail = ? ", "ss", array($email, $email))->num_rows > 0;
    }
    public function deleteUserByUserID(int $id)
    {
        return parent::ExecuteEditQuery("DELETE FROM users WHERE UserID = ?", 'i', array($id));
    }
    public function insertUser(array $assocdata)
    {
        // Since the admin makes this account, the email is already set to verified 
        $query = "INSERT INTO users (Username, Password, VerifiedMail, Role, Date_of_birth)
                  VALUES (?, ?, ?, ?, ?);";
        $parameters = array($assocdata['username'], $assocdata['password'], $assocdata['email'], $assocdata['Role'], $assocdata['bday']);
        return parent::ExecuteEditQuery($query, 'sssis', $parameters);
    }
    public function MailUserUpdates(User $changeduser, bool $passwordChange, bool $usernameChange, bool $emailChange )
    {
        $subject = "Account settings have been updated";
        $message = 
"Your account on WordLearner has just been edited, the following settings have been altered: " 
    . ($passwordChange ? '-password ' : '') . ($usernameChange ? '-username ' : '') . ($emailChange ? '-email ' : '');
       

        $mail = new PHPMailer(true);
        $mail->isSMTP(); 
        $mail->Host = MAIL_SERVER;
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = MAIL_USERNAME;                      // SMTP username
        $mail->Password   = MAIL_PASSWORD;                      // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USERNAME, 'WordLearner');
        $mail->addAddress($changeduser->GetCorrectEmail());
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $mail->send();
    }

}