<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';
  
  $versions = file_get_contents('http://127.0.0.1/update_packages/version_list.php'); //@jtPox insert the real IP here
  
  if($versions != '') 
  {
        $versionList = explode("|", $versions);
        foreach($versionList as $version) 
        {
            if( version_compare(TANGOBB_VERSION, $version, '<') ) 
            {
                echo '<p>New version found: ' . $version . '<br /><a href="?doUpdate=true&step=1">&raquo; Download Now?</a></p>';
                
            // do update
                // Step 1 downloading the file
                if (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 1)
                {
                    $ADMIN->download('http://127.0.0.1/update_packages/Iko_update_package_'.$version.'.zip', false);
                    echo '<p><a href="?doUpdate=true&step=2">&raquo; Install Now?</a></p>';
                }
                // Step 2 Extract zip
                elseif(isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 2)
                {
                    echo $ADMIN->zip_extract('updates/Iko_update_package_'.$version.'.zip', true, true);    
                    
                    echo '<p><a href="?doUpdate=true&step=3">&raquo; Last step</a></p>';
                }
                // Step 3 Execute upgrade.php
                elseif(isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 3) {
                    if(is_file('updates/upgrade.php')) include('updates/upgrade.php');
                
                }
                
            }
        }
  }
  
  
  
  
  require_once('template/bot.php');

?>