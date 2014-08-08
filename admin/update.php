<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';
  
  $versions = file_get_contents('http://127.0.0.1/update_packages/version_list.php'); //@jtPox insert the real IP here
  
  if($versions != '') {
        $versionList = explode("|", $versions);
        foreach($versionList as $version) {
            if( version_compare(TANGOBB_VERSION, $version, '<') ) {
                echo '<p>New version found: ' . $version . '<br /><a href="?doUpdate=true&step=1">&raquo; Download Now?</a></p>';
                
            // do update
                if (isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 1) {
                    
                    // Step 1 downloading the file
                    if ( !is_file(  'updates/Iko_update_package_'.$version.'.zip' )) {
                        $newUpdate = curl_init('http://127.0.0.1/update_packages/Iko_update_package_'.$version.'.zip'); //@jtPox insert the real IP here
                        if ( !is_dir( 'updates/' ) ) mkdir ( 'updates/' );
                        $dlHandler = fopen('updates/Iko_update_package_'.$version.'.zip', 'w');
                        curl_setopt($newUpdate, CURLOPT_FILE, $dlHandler);
                        curl_setopt($newUpdate, CURLOPT_TIMEOUT, 3600);
                        curl_exec($newUpdate);
                        fclose($dlHandler);
                        echo '<p>Download successful.</p>';
                    } 
                    else echo '<p>Update already downloaded.</p>'; 
                    echo '<p><a href="?doUpdate=true&step=2">&raquo; Install Now?</a></p>';
                }
                elseif(isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step'] == 2) {
                    
                    $zipHandle = zip_open('updates/Iko_update_package_'.$version.'.zip');
                    
                    
                    // Step 2 extracting
                    $i = 0;
                    $file_message = array();
                    $error = array();
                    while ($file = zip_read($zipHandle) )
                    {
                        $i++;
                        $thisFileName = zip_entry_name($file);
                        $thisFileDir = dirname($thisFileName);
                        
                        if(!zip_entry_open($zipHandle, $file, 'r'))
                        {
                            $error[$i] = '#1 File could not be handled: '.$zname.'<br />';
                            continue;
                        }
                        if(!is_dir($thisFileDir))
                        {
                            $file_message[$i] = '<li>' . $thisFileDir . ': ';
                            mkdir($thisFileDir, 0755);
                        }
                        $zip_filesize = zip_entry_filesize($file);
                        if(empty($zip_filesize))
                        {
                            if(substr($thisFileName, -1) == '/')
                            {
                                $file_message[$i] = '<li>' . $thisFileName . ': ';
                                if(!is_dir('../'.$thisFileName)) {
                                    mkdir('../'.$thisFileName, 0755);
                                }
                                else
                                {
                                    $error[$i] = '-> File already exists.';
                                }
                                
                                unset($thisFileDir);
                                unset($thisFileName);
                                continue;
                            }
                        }
                        
                        $content = zip_entry_read($file, $zip_filesize);
                        
                        if($thisFileName == 'upgrade.php'){
                            $file_message[$i] = '<li>' . $thisFileName . ': ';
                            if(@file_put_contents('updates/'.$thisFileName, $content) === false)
                            {
                                $error[$i] = 'File could not be handled: '.$thisFileName.'<br />';
                            }
                        }
                        else {
                            $file_message[$i] = '<li>' . $thisFileName . ': ';
                            if(@file_put_contents('../'.$thisFileName, $content) === false)
                            {
                                $error[$i] = '#2 File could not be handled: '.$thisFileName.'<br />';
                            }
                        }
                        

                        zip_entry_close($file);

                        unset($thisFileDir);
                        unset($thisFileName);
                    }

                    zip_close($zipHandle);
                
                
                echo '<ul>';
                foreach($file_message as $i => $message)
                {
                    echo $i . ': '.$message;
                    if(@$error[$i]=='') {
                        echo '-> Done';
                    }
                    else
                    {
                        echo $error[$i];
                    }
                    echo '</li>';
                }
                echo '</ul>';
                echo '<p><a href="?doUpdate=true&step=3">&raquo; Last step</a></p>';
                }
                
                // Step 3 Execute upgrade.php
                elseif(isset($_GET['doUpdate']) && $_GET['doUpdate'] == true && isset($_GET['step']) && $_GET['step']==3) {
                if(is_file('updates/upgrade.php')) include('updates/upgrade.php');
                
                }
                
            }
        }
  }
  
  
  
  
  require_once('template/bot.php');

?>