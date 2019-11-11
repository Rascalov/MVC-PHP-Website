<?php 

// Probably not the best way, but I got tired of setting up switch statements for every status for logging in, signing up, and all future stuff.
// So I made a class with a static assoc array that simply gives the status message corresponding to the set url status ($_GET['error'] or similair)
// originally called ErrorMessage, started using it with pretty much any status i gave as feedback, so decided to change the name
abstract class StatusMessage
{
    public static $Statusses = array
    (
        'pswnotequal' => '<div class="error"><b>The password and the repeat password did not match!<b></div>' ,
        'emailexists' => '<div class="error"><b>This email is already in use!<b></div>',
        'userexists' => '<div class="error"><b>This Username is already in use!<b></div>',
        'failure' => '<label class="error"><b>An error has occured, please try again later!</b></label><br>',
        'norows' => '<label class="error"><b>Username or password is incorrect!</b></label><br>',
        'pswchange' => '<label><b>Your login password has been changed.</b></label><br>',
        'failedval' => '<label class="error"><b>Unable to change password</b></label><br>',
        'vmailsent' => '<label><b>An email has been sent to verify your email</b></label><br>',
        'updated' => '<label><b>Your changes have been saved!</b></label><br>',
        'usercreated' => '<label><b>User has been created</b></label><br>',
        'invalidcaptcha' => '<label class="error"><b>Invalid Captcha!</b></label><br>'
        
    );
}