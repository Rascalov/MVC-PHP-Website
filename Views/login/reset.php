<form action="resetCheck" method="post" style="border:1px solid #ccc">
    <div class="container">
        <h1>Reset Password</h1>
        <?php
        if (isset($_GET['error'])) {
            echo @StatusMessage::$Statusses[$_GET['error']];
        }
        ?>
        <p>Please insert your new password</p>
        <hr>
        <label for="SignupPsw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="ResetPsw" required>

        <label for="SignupPsw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="ResetPsw-repeat" required>

        <input hidden name="UserID"  value=<?php echo $this->UserID?>>
            <div class="clearfix">
                <button type="submit" class="signupbtn">Reset</button>
            </div>
    </div>
</form>