<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';
  
  $versions = file_get_contents('http://127.0.0.1/update_packages/version_list.php'); //@jtPox insert the real IP here
  if($versions!='') {
        $versionList = explode("|", $versions);
        foreach($versionList as $version) {
            if( version_compare(TANGOBB_VERSION, $version, '<') ) {
                echo 'New version found: '.$version;
                
                if ( !is_file(  'updates/Iko_update_package_'.$version.'.zip' )) {
                    
                    $newUpdate = curl_init('http://127.0.0.1/update_packages/Iko_update_package_'.$version.'.zip'); //@jtPox insert the real IP here
                    if ( !is_dir( 'updates/' ) ) mkdir ( 'updates/' );
                    $dlHandler = fopen('updates/Iko_update_package_'.$version.'.zip', 'w');
                    curl_setopt($newUpdate, CURLOPT_FILE, $dlHandler);
                    curl_setopt($newUpdate, CURLOPT_TIMEOUT, 3600);
                    curl_exec($newUpdate);
                    fclose($dlHandler);
                    echo '<p>DL erfolgreich</p>';
                } else echo '<p>Update already downloaded.</p>'; 
                
            // do update
                if (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true) {
                    
                    $zipHandle = zip_open('updates/Iko_update_package_'.$version.'.zip');
                    echo '<ul>';
                    while ($file = zip_read($zipHandle) )
                    {
                        $thisFileName = zip_entry_name($file);
                        $thisFileDir = dirname($thisFileName);
                        if(!zip_entry_open($zipHandle, $file, 'r'))
                        {
                            $error .= 'Datei konnte nicht verarbeitet werden: '.$zname.'<br />';
                            continue;
                        }
                        if(!is_dir($thisFileDir))
                        {
                            mkdirr($thisFileDir, 0755);
                        }
                        $zip_filesize = zip_entry_filesize($file);
                        if(empty($zip_filesize))
                        {
                            if(substr($thisFileName, -1) == '/')
                            {
                                mkdir('../'.$thisFileName, 0755);
                                unset($thisFileDir);
                                unset($thisFileName);
                                continue;
                            }
                        }
                        
                        $content = zip_entry_read($file, $zip_filesize);
                        
                        if($thisFileName == 'upgrade.php'){
                            if(@file_put_contents('updates/'.$thisFileName, $content) === false)
                            {
                                $error .= 'Datei konnte nicht verarbeitet werden: '.$thisFileName.'<br />';
                            }
                            include('updates/upgrade.php');
                        }
                        else{
                            if(@file_put_contents('../'.$thisFileName, $content) === false)
                            {
                                $error .= 'Datei konnte nicht verarbeitet werden: '.$thisFileName.'<br />';
                            }
                        }
                        

                        zip_entry_close($file);

                        unset($thisFileDir);
                        unset($thisFileName);
                    }

                    zip_close($zipHandle);
                }
                else echo '<p>Update ready. <a href="?doUpdate=true">&raquo; Install Now?</a></p>';
                /* if ($_GET['doUpdate'] == true) {
                //Open The File And Do Stuff
                $zipHandle = zip_open($_ENV['site']['files']['includes-dir'].'/UPDATES/MMD-CMS-'.$aV.'.zip');
                echo '<ul>';
                while ($aF = zip_read($zipHandle) )
                {
                    $thisFileName = zip_entry_name($aF);
                    $thisFileDir = dirname($thisFileName);
                   
                    //Continue if its not a file
                    if ( substr($thisFileName,-1,1) == '/') continue;
                   
    
                    //Make the directory if we need to...
                    if ( !is_dir ( $_ENV['site']['files']['server-root'].'/'.$thisFileDir ) )
                    {
                         mkdir ( $_ENV['site']['files']['server-root'].'/'.$thisFileDir );
                         echo '<li>Created Directory '.$thisFileDir.'</li>';
                    }
                   
                    //Overwrite the file
                    if ( !is_dir($_ENV['site']['files']['server-root'].'/'.$thisFileName) ) {
                        echo '<li>'.$thisFileName.'...........';
                        $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                        $contents = str_replace("\\r\\n", "\\n", $contents);
                        $updateThis = '';
                       
                        //If we need to run commands, then do it.
                        if ( $thisFileName == 'upgrade.php' )
                        {
                            $upgradeExec = fopen ('upgrade.php','w');
                            fwrite($upgradeExec, $contents);
                            fclose($upgradeExec);
                            include ('upgrade.php');
                            unlink('upgrade.php');
                            echo' EXECUTED</li>';
                        }
                        else
                        {
                            $updateThis = fopen($_ENV['site']['files']['server-root'].'/'.$thisFileName, 'w');
                            fwrite($updateThis, $contents);
                            fclose($updateThis);
                            unset($contents);
                            echo' UPDATED</li>';
                        }
                    }
                }
                echo '</ul>';
                $updated = TRUE;
            }
            else echo '<p>Update ready. <a href="?doUpdate=true">&raquo; Install Now?</a></p>'; */
            }
        }
  }
  
  
  
  
  require_once('template/bot.php');

?>