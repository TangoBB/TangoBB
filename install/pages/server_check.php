<div class="panel-heading">
    Checking your server.
</div>
<?php
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    $check['php'] = false;
    $check['php_version'] = PHP_VERSION;
    $check['php_css'] = 'danger';
} else {
    $check['php'] = true;
    $check['php_version'] = PHP_VERSION;
    $check['php_css'] = 'success';
}

if (extension_loaded('pdo_mysql')) {
    $check['pdo'] = 'Installed';
    $check['pdo_css'] = 'success';
} else {
    $check['pdo'] = 'Not Installed';
    $check['pdo_css'] = 'danger';
}
$config_chmods = substr(decoct(fileperms("../../applications/config.php")), -3);
$check['OS'] = php_uname('s');
$check['OS_css'] = 'success';
if (strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN') {
    if ($config_chmods < '666') {
        $check['chmods'] = false;
        $check['chmods_value'] = $config_chmods;
        $check['chmods_css'] = 'danger';
    } else {
        $check['chmods'] = true;
        $check['chmods_value'] = $config_chmods;
        $check['chmods_css'] = 'success';
    }
} else {
    if ($config_chmods < '777') {
        $check['chmods'] = false;
        $check['chmods_value'] = $config_chmods;
        $check['chmods_css'] = 'danger';
    } else {
        $check['chmods'] = true;
        $check['chmods_value'] = $config_chmods;
        $check['chmods_css'] = 'success';
    }
}


if ($check['php'] === true && $check['chmods'] === true) {
    $_SESSION['tangobb_install_step1'] = true;
    ?>
    <div class="panel-body">
        <div class="alert alert-success">
            System check done! <a href="javascript:return false;" onclick="javascript:ajaxLoad('pages/mysql.php')">Continue</a>.
        </div>
    </div>
<?php
} else {
    ?>
    <div class="panel-body">
        <div class="alert alert-danger">
            <strong>Oh snap!</strong>
            TangoBB can't be installed on your system.
            <?php
            if ($check['php'] === false) {
                echo '<br />Your current PHP version is lower than the recommended version.';
            }
            if ($check['chmods'] === false) {
                echo '<br />Please change the chmod of the \'<em>config.php</em>\' file in the \'<em>applications</em>\' folder to <em>777</em>.';
            }
            ?>
        </div>
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
        <td>Operating system</td>
        <td><span class="label label-default"></span></td>
        <td><span class="label label-<?php echo $check['OS_css']; ?>"><?php echo $check['OS']; ?></span></td>
    </tr>
    <tr>
        <td>PHP Version</td>
        <td><span class="label label-default">5.3.3 +</span></td>
        <td><span class="label label-<?php echo $check['php_css']; ?>"><?php echo $check['php_version']; ?></span></td>
    </tr>
    <tr>
        <td>PDO Extension</td>
        <td><span class="label label-default">Installed</span></td>
        <td><span class="label label-<?php echo $check['pdo_css']; ?>"><?php echo $check['pdo']; ?></span></td>
    </tr>
    <tr>
        <td>chmod '<em>applications/config.php</em>'</td>
        <td><span
                class="label label-default"><?php echo (strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN') ? ('666') : ('777') ?> </span>
        </td>
        <td><span class="label label-<?php echo $check['chmods_css']; ?>"><?php echo $check['chmods_value']; ?></span>
        </td>
    </tr>
</table>
