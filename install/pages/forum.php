<?php
define('BASEPATH', '1');
//die('1');
require_once('../../applications/config.php');
?>
<div class="panel-heading">
    Forum settings.
</div>
<div class="panel-body">
    <?php
    if (isset($_POST['submit'])) {
        try {
            $name = $_POST['name'];
            $email = $_POST['email'];
            if (!$name or !$email) {
                throw new Exception('All fields are required!');
            } else {
                //$MYSQL = new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

                $dsn = 'mysql:dbname=' . MYSQL_DATABASE . ';host=' . MYSQL_HOST;

                try {
                    $MYSQL = new PDO($dsn, MYSQL_USERNAME, MYSQL_PASSWORD);
                } catch (PDOException $e) {
                    throw new Exception('Connection failed: ' . $e->getMessage());
                }

                /*if( $MYSQL->query("INSERT INTO `" . MYSQL_PREFIX . "blog` (`id`, `blog_name`, `blog_theme`, `blog_language`) VALUES (1, '$name', 'up', 'english');") ) {
                    echo '<div class="alert alert-success">Success! <a href="javascript:ajaxLoad(\'pages/user.php\')">Continue</a>.</div>';
                } else {
                    throw new Exception('Error inserting blog details into database.');
                }*/

                $rules = '
- No spamming.
- No racist comments.
- Do not start a political discussion unless permitted.
- No illegal stuff are to be posted on anywhere in the forum.';
                $MYSQL->query("INSERT INTO `" . MYSQL_PREFIX . "generic` (`id`, `site_rules`, `site_name`, `site_theme`, `site_language`, `site_email`) VALUES ('1', '$rules', '$name', '1', 'english', '$email');");
                echo '<div class="alert alert-success">Success! <a href="javascript:return false;" onclick="javascript:ajaxLoad(\'pages/user.php\')">Continue</a>.</div>';

            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }
    }
    ?>
    <form action="javascript:return false;" onsubmit="javascript:ajaxForm('pages/forum.php')" class="ajaxForm"
          method="POST">
        <input type="text" name="name" class="form-control input-lg" placeholder="Forum Name"/>
        <input type="text" name="email" class="form-control input-lg" placeholder="Forum Email"/>
        <br/>
        <input type="hidden" name="submit" value=""/>
        <input type="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Continue"/>
    </form>
</div>
