<?php if ((Session::exists())) : ?>
    <h1> Welcome <?php echo Session::get('Username') ?></h1>

<?php else : ?>
    <h1>Welcome to WordLearner 1.0! As the dutch say: "het is wrts, maar dan niet" </h1>
<?php endif ?>