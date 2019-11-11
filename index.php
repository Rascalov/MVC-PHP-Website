<?php
// todo : autoloader
//Require the following files to get it all working:
require 'libs/StatusMessage.php';
require 'libs/Controller.php';
require 'libs/Bootstrap.php';
require 'libs/Session.php';
require 'libs/Model.php';
require 'libs/Role.php';
require 'libs/View.php';
require 'libs/PHPMailer.php';
require 'libs/SMTP.php';
require 'libs/Exception.php';

require 'config/database.php';
require 'config/paths.php';
require 'config/mail.php';

// Construct the bootstrap class that delegates the path of the url to the controllers
$app = new Bootstrap();