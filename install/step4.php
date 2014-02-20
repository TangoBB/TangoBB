<?php

  require_once('assets/top.php');

  if( !isset($_SESSION['tangobb_install_step2']) ) {
      die('Installation access denied.');
  }

  define('Install', '');
  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');
  require_once('../applications/functions.php');

  if( isset($_POST['continue']) ) {
      try {

          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = htmlentities($child);
          }

          $username = $_POST['username'];
          $password = encrypt($_POST['password']);
          $email    = $_POST['email'];
          $date     = time();

          if( !$username or !$password or !$email ) {
              throw new Exception ('All fields are required!');
          } else {

              $data = array(
                  'username' => $username,
                  'user_password' => $password,
                  'user_email' => $email,
                  'date_joined' => $date,
                  'user_group' => ADMIN_ID
              );

              if( $MYSQL->insert('{prefix}users', $data) ) {
                  echo '<div class="alert alert-success">TangoBB has been successfully installed! Please delete the installation folder.</div>';
              } else {
                  throw new Exception ('Error inserting user into database!');
              }

          }

      } catch ( Exception $e ) {
          echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
      }
  }

?>
<form action="" method="POST">
	<label for="username">Username</label>
    <input type="text" name="username" id="username" class="form-control" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" class="form-control" />
    <label for="email">Email</label>
    <input type="text" name="email" id="email" class="form-control" />
    <br />
	<input type="submit" name="continue" value="Continue" class="btn btn-default" />
</form>
<?php

  require_once('assets/bot.php');

?>