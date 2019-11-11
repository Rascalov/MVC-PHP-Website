<!DOCTYPE html>
<html>

<head>
    <title>WordLearner</title>
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
</head>
<?php @Session::init() ?>

<body>
    <aside></aside>
    <main>
        <nav>

            <div class="topnav">

                <!-- Centered link -->
                <div class="topnav-centered">
                    <h2 class="navTitle">WordLearner 1.0</h2>
                </div>

                <?php if ((Session::exists())) : ?>
                    <!-- Left-aligned links (logged in users) -->
                    <a href="<?php echo URL; ?>index">Home</a>
                    <a href="<?php echo URL . 'users/user?name=' . Session::get('Username') ?>">My Account</a>
                    <?php if (Session::get('Role') == Role::SuperAdmin) : ?>
                        <a href="<?php echo URL ?>users/create">Create Users</a>
                    <?php endif; ?>
                    <!-- Right-aligned links (logged in users)  -->
                    <div class="topnav-right">
                        <a href="<?php echo URL; ?>users">Browse Users</a>
                        <a href="<?php echo URL; ?>wordlist">My Wordlists (coming soon)</a>
                        <a href="<?php echo URL; ?>login/logout">Logout</a>
                    </div>



                <?php else : ?>
                    <!-- Left-aligned links (default) -->
                    <a href="<?php echo URL; ?>index">Home</a>

                    <!-- Right-aligned links (default) -->
                    <div class="topnav-right">
                        <a href="<?php echo URL; ?>signup">Sign up</a>

                        <a href="<?php echo URL; ?>login">Login</a>
                    </div>


                <?php endif; ?>
            </div>
        </nav>