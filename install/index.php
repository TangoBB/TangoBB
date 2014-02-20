<?php

session_start();

if( version_compare(PHP_VERSION, '5.4', '>=') ) {

    $_SESSION['tangobb_install_step1'] = true;

    require_once('assets/top.php');
?>
<div class="alert alert-success">
    System check done! <a href="step2.php">Continue</a>.
</div>
<?php
} else {
?>
<div class="alert alert-danger">
    <strong>Oh snap!</strong>
    TangoBB is incompatible with your current PHP version.
</div>
Recommended PHP Version: <strong>5.4</strong> or newer.<br />
Your current PHP Version: <?php echo PHP_VERSION; ?>
<?php
}

require_once('assets/bot.php');

?>