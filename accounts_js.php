<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  header('Content-Type: application/javascript');

  $query = $MYSQL->get('{prefix}users');
  $users = array();

  foreach( $query as $u ) {
  	$users[] = '\'' . $u['username'] . '\'';
  }

  $users = implode(', ', $users);

?>
$(document).ready(function() {
	$('#receiver').typeahead({
	    name: 'users',
	    local: [<?php echo $users; ?>]
	});
});