<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

$versions = @file_get_contents('http://api.codetana.com/iko/version_list.php'); //@jtPox insert the real IP here

if ($versions != '') {
    $versionList = explode("|", $versions);
    foreach ($versionList as $version) {
        if (version_compare(TANGOBB_VERSION, $version, '<')) {
            //echo '<p>New version found: ' . $version . '<br /><a href="?doUpdate=true&step=1">&raquo; Download Now?</a></p>';

            // do update
            // Step 1 downloading the file
            if (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 1) {
                $ADMIN->download('http://api.codetana.com/iko/update_packages/Iko_update_package_' . $version . '.zip', true);
                echo '<div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;">
                                33%
                            </div>
                          </div>';
                echo '<p><a href="?doUpdate=true&step=2">&raquo; Install Now?</a></p>';
            } // Step 2 Extract zip
            elseif (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 2) {
                if (is_file('updates/Iko_update_package_' . $version . '.zip')) {
                    $output = $ADMIN->zip_extract('updates/Iko_update_package_' . $version . '.zip', true, true);
                } else {
                    $output = '<div class="alert alert-danger" role="alert">Downloaded file not found!</div>';
                }
                echo '<div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style="width: 66%;">
                                66%
                            </div>
                          </div>';
                echo $output;

                echo '<p><a href="?doUpdate=true&step=3">&raquo; Last step</a></p>';
            } // Step 3 Execute upgrade.php
            elseif (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 3) {
                if (is_file('updates/upgrade.php')) include('updates/upgrade.php');
                echo '<div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                100%
                            </div>
                          </div>';

            }

        }
    }
}


require_once('template/bot.php');

?>
