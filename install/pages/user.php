<?php
  define('BASEPATH', 'Install');
  define('Install', '');
  require_once('../../applications/wrapper.php');
  require_once('../../applications/functions.php');
?>
<div class="panel-heading">
	Administrator settings.
</div>
<div class="panel-body">
	<?php
	  if( isset($_POST['submit']) ) {
	  	try {

	  		foreach( $_POST as $parent => $child ) {
                $_POST[$parent] = htmlentities($child);
            }

            $username = $_POST['username'];
            $password = encrypt($_POST['password']);
            $email    = $_POST['email'];
            $date     = time();

	  		if( !$username or !$email or !$password ) {
	  			throw new Exception('All fields are required!');
	  		} else {

                $MYSQL->query("INSERT INTO `{prefix}users` (`id`, `username`, `user_password`, `user_email`, `date_joined`, `user_group`) VALUES (NULL, '$username', '$password', '$email', '$date', '" . ADMIN_ID . "');");
                echo '<div class="alert alert-success">TangoBB has been successfully installed! Please delete the installation folder.</div>';

	  		}
	  	} catch (Exception $e) {
	  		echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
	  	}
	  }
	?>
	<form action="#" onsubmit="javascript:ajaxForm('pages/user.php')" class="ajaxForm" method="POST">
		<input type="text" name="username" class="form-control input-lg" placeholder="Username" />
        <input type="password" name="password" class="form-control input-lg" placeholder="Password" />
        <input type="text" name="email" class="form-control input-lg" placeholder="Email" />
        <br />
        <input type="hidden" name="submit" value="" />
        <input type="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Finish" />
	</form>
</div>
