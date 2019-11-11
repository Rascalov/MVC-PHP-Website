<form action="signup/createUser" method="post" style="border:1px solid #ccc">
    <div class="container">
        <h1>Sign Up</h1>
        <?php
        if (isset($_GET['error'])) {
            echo @StatusMessage::$Statusses[$_GET['error']]; // @ = ignore warningss
        }
        ?>
        <p>Please fill in this form to create an account.</p>
        <hr>
        <?php include 'Captcha.php'; ?>
        <label for="SignupUsername"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="SignupUsername" required>

        <label for="SignupEmail"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="SignupEmail" required>

        <label for="SignupPsw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="SignupPsw" required>

        <label for="SignupPsw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="SignupPsw-repeat" required>

        <label for="SignupBday"><b>Date of Birth</b><br></label>
        <input type="date" name="SignupBday" required>

        <div style="display:block;margin-bottom:20px;margin-top:20px;">
            <img src="captcha_image.png">
        </div>
        <a href="">New captcha</a>
        <input type="text" placeholder="Enter Captcha" name="Captcha" required>

        <p>By creating an account you agree to our <a href="#ja, dikke vinger dat ik die ga schrijven" style="color:dodgerblue">Terms & Privacy</a>.</p>

        <div class="clearfix">
            <button type="submit" class="signupbtn">Sign Up</button>
        </div>
    </div>
</form>