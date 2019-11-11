<form action="login/check" method="post">
    <div class="imgcontainer">
        <img src="<?php echo URL ?>public/images/learnimage.jpg" alt="Avatar" class="avatar">
    </div>

    <?php include 'Captcha.php'; ?>
    <div class="container">
        <?php
        if (isset($_GET['error'])) {
            echo @StatusMessage::$Statusses[$_GET['error']];
        }
        ?>
        <label for="Loginuname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="Loginuname" required>

        <label for="Loginpsw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="Loginpsw" required>
        <div style="display:block;margin-bottom:20px;margin-top:20px;">
            <img src="captcha_image.png">
        </div>
        <a href="">New captcha</a>
        <input type="text" placeholder="Enter Captcha" name="LoginCaptcha" required>
        <button type="submit">Login</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
        <span class="psw">Forgot <a href="login/forgot">password?</a></span>
    </div>
</form>