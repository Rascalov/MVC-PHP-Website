<form action="forgotCheck" method="post">
    <div class="imgcontainer">
        <img src="<?php echo URL ?>public/images/learnimage.jpg" alt="Avatar" class="avatar">
    </div>

    <div class="container">
        <?php
        if (isset($_GET['error'])) {
            echo @StatusMessage::$Statusses[$_GET['error']];
        }
        if(isset($_GET['status']))
        {
            if($_GET['status'] == 'success'){
                echo 'Success! We sent you a mail which will guide you further. <br>';
            }
        }
        ?>
        <label for="ForgotEmail"><b>Enter your <i>verfied</i> Email</b></label>
        <input type="text" placeholder="Enter Email" name="ForgotEmail" required>
        <button type="submit">Mail password recovery link</button>
    </div>
</form>