<form action="createUser" method="post" style="border:1px solid #ccc">
    <div class="container">
        <h1>Create User</h1>
        <?php
        if (isset($_GET['error'])) {
            echo @StatusMessage::$Statusses[$_GET['error']]; // @ = ignore warningss
        }
        ?>
        <p>Please fill in this form to create an user.</p>
        <hr>

        <label for="SignupUsername"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="SignupUsername" required>

        <label for="SignupEmail"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" name="SignupEmail" required>

        <label for="SignupPsw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="SignupPsw" required>

        <label for="SignupPsw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="SignupPsw-repeat" required>

        <label for="SignupBday"><b>Date of Birth</b><br></label>
        <input type="date" name="SignupBday" required><br>

        <label for="SignupRole"><b>Role</b></label><br>
        <select required name='SignupRole'>
            <option value="0">User</option>
            <option value="1">Admin</option>
        </select><br><br><br><br><br>

        <div class="clearfix">
            <button type="submit" class="signupbtn">Create User</button>
        </div>
    </div>
</form>