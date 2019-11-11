<?php include 'Captcha.php'?>
<form action="users" method="get" style="border:1px solid #ccc">
    <label for="searchquery"><b>Search by username, email, or registration date</b><br></label>
    <input type="text" name="searchquery" id="Textboxexpand" placeholder="Search.." required>
    <div style="display:block;margin-bottom:20px;margin-top:20px;">
        <img src="captcha_image.png">
    </div>
    <a href="users">New captcha</a> <br>
    <input type="text" id="Textboxexpand" placeholder="Enter Captcha" name="Captcha" required>
    <button type="submit">Search</button>
</form>

<?php


if (isset($_GET['searchquery'])) {

    $count = @count($this->userlist);
    if ($count == 0 || $this->userlist === null) {
        echo '<h1>No users found matching the given criteria</h1>';
    } else {
        // Echo out a table and a row for each found user
        echo ('<h1>Results: </h1>');
        echo '<table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                </tr>';
        foreach ($this->userlist as $user) {
            echo ("<tr>
                    <td><a href=" . URL . "users/user?name=$user->Username>$user->Username</a></td>
                    <td>" . $user->GetCorrectEmail() . "</td>
                    <td>" . $user->RegistrationDate->format('d-M-Y') . "</td
                    </tr>");
        }
        echo '</table>';
    }
}

?>