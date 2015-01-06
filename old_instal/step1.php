<?php

session_start();
require_once('assets/top.php');

if( version_compare(PHP_VERSION, '5.3', '<') ) {
    $check['php'] = false;
    $check['php_version'] = PHP_VERSION;
    $check['php_css'] = 'danger';
}
else {
    $check['php'] = true;
    $check['php_version'] = PHP_VERSION;
    $check['php_css'] = 'success';
}
if(function_exists('mysqli_connect')){
    $check['mysql'] = true;
    $check['mysql_version'] = 'Installed';
    $check['mysql_css'] = 'success';
}
else {
    $check['mysql'] = false;
    $check['mysql_version'] = 'Not installed';
    $check['mysql_css'] = 'danger';
}
$config_chmods = substr(decoct(fileperms("../applications/config.php")), -3);
if($config_chmods < '666') {
    $check['chmods'] = false;
    $check['chmods_value'] = $config_chmods;
    $check['chmods_css'] = 'danger';
}
else {
    $check['chmods'] = true;
    $check['chmods_value'] = $config_chmods;
    $check['chmods_css'] = 'success';
}

if($check['php'] === true && $check['chmods'] === true && $check['mysql'] === true) {
     $_SESSION['tangobb_install_step1'] = true;
     ?>
     <div class="alert alert-success">
        System check done! <a href="step2.php">Continue</a>.
     </div>
    <?php
}
else {
?>
    <div class="alert alert-danger">
        <strong>Oh snap!</strong>
        Iko can't be installed on your system.
        <?php
        if($check['php']===false) { echo '<br />Your current PHP version is lower than the recommended version.';}
        if($check['chmods']===false) { echo '<br />Please change the chmod of the \'<em>config.php</em>\' file in the \'<em>applications</em>\' folder to <em>777</em>.';}
        if($check['mysql']===false) { echo '<br />Please check if you have installed mysql.';}
        ?>
    </div>
<?php
}
?>

<table class="table">
    <thead>
        <tr>
            <th width="60%"></th>
            <th>Recommended:</th>
            <th>Your system:</th>
        </tr>
    </thead>
    <tr>
        <td>PHP Version</td>
        <td><span class="label label-default">5.3.3 +</span></td>
        <td><span class="label label-<?php echo $check['php_css']; ?>"><?php echo $check['php_version']; ?></span></td>
    </tr>
    <tr>
        <td>mySQL</td>
        <td><span class="label label-default">Installed</span></td>
        <td><span class="label label-<?php echo $check['mysql_css']; ?>"><?php echo $check['mysql_version']; ?></span></td>
    </tr>
    <tr>
        <td>chmod '<em>applications/config.php</em>'</td>
        <td><span class="label label-default">666 +</span></td>
        <td><span class="label label-<?php echo $check['chmods_css']; ?>"><?php echo $check['chmods_value']; ?></span></td>
    </tr>
</table>

<?php


require_once('assets/bot.php');

?>
