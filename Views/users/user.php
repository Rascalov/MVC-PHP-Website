<div class="card">
    <img src="<?php echo URL ?>public/images/learnimage.jpg" alt="Avatar" class="avatar">
    <?php if ($this->CanEdit) : ?>
        <!-- When admin or it's your own profile, the values are editable-->
        <form action="update" method="post">
            <div class="container">
                <?php
                    if (isset($_GET['error'])) {
                        echo @StatusMessage::$Statusses[$_GET['error']];
                    }
                    ?>
                <input type="hidden" name="User-identifier" value="<?php echo $this->user->Username ?>">
                <label for="Loginuname"><b>Username</b></label><br>
                <input type="text" id="Textboxexpand" placeholder="Enter Username" value="<?php echo $this->user->Username ?>" name="User-Username" required><br>

                <label for="UserEmail"><b>Email</b></label><br>
                <input type="text" id="Textboxexpand" placeholder="Enter email" value="<?php echo $this->user->GetCorrectEMail() ?>" name="User-Email" required><br>

                <label for="Userpsw"><b>New Password</b></label><br>
                <input type="password" id="Textboxexpand" placeholder="Enter Password" name="User-psw"><br>

                <label for="Userpsw-repeat"><b>Repeat New Password</b></label><br>
                <input type="password" id="Textboxexpand" placeholder="Repeat Password" name="User-psw-repeat"><br>

                <div class="clearfix">
                    <button type="submit" class="signupbtn">Save changes</button>
                </div>

            </div>
        </form>
        <?php if (Session::get('Role') == Role::Admin && $this->user->Username != Session::get('Username')) : ?>
            <form action="takeover" method="post">
                <input type="hidden" name="User-identifier" value="<?php echo $this->user->Username ?>">
                <button type="submit">Log in on this account</button>
            </form>
        <?php elseif (Session::get('Role') == Role::SuperAdmin && $this->user->Username != Session::get('Username')) : ?>
            <form action="delete" method="post">
                <input type="hidden" name="User-identifier" value="<?php echo $this->user->Username ?>">
                <button type="submit">Delete this User</button>
            </form>
        <?php endif; ?>


    <?php else : ?>
        <h1><?php echo $this->user->Username ?></h1>
        <p><?php echo $this->user->GetAge() ?> years old</p>
        <p>Registered on: <?php echo $this->user->RegistrationDate->format('d-M-Y') ?></p>

    <?php endif; ?>
</div>