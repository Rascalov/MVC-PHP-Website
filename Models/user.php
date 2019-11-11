<?php
class User
{
    private $properties;

    public function __construct(int $userID, string $username, $email, $verifiedEmail, DateTime $registrationDate, DateTime $dateofBirth, int $role)
    {
        $this->properties['UserID'] = $userID;
        $this->properties['Username'] = $username;
        $this->properties['Email'] = $email;
        $this->properties['VerifiedEmail'] = $verifiedEmail;
        $this->properties['RegistrationDate'] = $registrationDate;
        $this->properties['DateOfBirth'] = $dateofBirth;
        $this->properties['Role'] = $role;
     }

    public  function __get($propertyName)
    {
        if (array_key_exists($propertyName, $this->properties)) {
            return $this->properties[$propertyName];
        }
    }
    public function __set($propertyName, $propertyValue)
    {
        if (array_key_exists($propertyName, $this->properties)) {
            $this->properties[$propertyName] = $propertyValue;
        }
    }
    public function GetAge()
    {
        $interval = $this->DateOfBirth->diff(date_create('today'));
        return $interval->format('%y');
    }
    public function GetCorrectEmail()
    {
        // I store a verified mail and a regular mail (incase the user changes his email), this is a short method to give preference to the verified mail if available
        return ($this->VerifiedEmail != null) ? $this->VerifiedEmail : $this->Email;
    }
}
