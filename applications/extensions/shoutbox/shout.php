<?php

define('BASEPATH', 'Extension');

	include('../../wrapper.php');
	
	global $MYSQL;
	
  	
	  $data  = array(
                    'id' => '5',
                    'timestamp' => '1386683085',
                    'user' => '1',
                    'post' => 'This is a post'
                );
                
                $MYSQL->insert('{prefix}shoutbox', $data);
 ?>
