<?php

if (!isset($_GET['vKey'])) {
    // if only email: send verification, let view display like 'mail has been sent'
    echo ('<h1>An email has been sent to verify your email address </h1> <br> <p><a href="">Resend verification mail</a></p> ');
    exit;
    }
if($this->verifySuccess)
    {
        echo '<h1>Thank you, your mail has been verified</h1>';
    }
    else
    {
        echo '<h1>Error, your mail could not be verified</h1>';
    }

?>