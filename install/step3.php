<?php

  define('Install', '');
  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !isset($_SESSION['tangobb_install_step2']) ) {
      die('Installation access denied.');
  }

  require_once('assets/top.php');

  if(isset($_POST['continue'])){
    	try {

            foreach( $_POST as $parent => $child ) {
                $_POST[$parent] = htmlentities($child);
            }

			$name  = $_POST['name'];
			$email = $_POST['desc'];

			/*
			 * Getting Site URL
			 */
			 $request  = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	         $site_url = explode('install/', $request);
	         $site_url = $site_url[0];

			if(!$name or !$email){
				throw new Exception('All fields are required!');
			}else{

                $rules = '
- No spamming.
- No racist comments.
- Do not start a political discussion unless permitted.
- No illegal stuff are to be posted on anywhere in the forum.';
                $data  = array(
                    'id' => 1,
                    'site_rules' => $rules,
                    'site_name' => $name,
                    'site_theme' => 'Blue',
                    'site_language' => 'english',
                    'site_email' => $email
                );

                try {
                    $MYSQL->insert('{prefix}generic', $data);
                    echo '<div class="alert alert-success">Success! <a href="step4.php">Continue</a>.</div>';
                } catch (mysqli_sql_exception $e) {
                    throw new Exception ('Error adding data into database.');
                }

			}

    	}catch(Exception $e){
    		echo '<div class="alert alert-danger">'.$e->getMessage().'</div>';
    	}
    }

?>
<form action="" method="POST">
	<label for="name">Forum Name</label>
	<input type="text" name="name" id="name" class="form-control" />
	<label for="desc">Administrator Email</label>
	<input type="text" name="desc" id="desc" class="form-control" />
    <br />
	<input type="submit" name="continue" value="Continue" class="btn btn-default" />
</form>
<?php

  require_once('assets/bot.php');

?>