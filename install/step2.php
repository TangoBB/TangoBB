<?php
  require_once('assets/top.php');

  if( !isset($_SESSION['tangobb_install_step1']) ) {
      die('Installation access denied.');
  }

  if(isset($_POST['mysql'])){
    	
		try {
				
			$mysql_host     = $_POST['mysql_h'];//MySQL Host.
    	    $mysql_username = $_POST['mysql_u'];//MySQL Username
		    $mysql_password = $_POST['mysql_p'];//MySQL Password
		    $mysql_database = $_POST['mysql_d'];//MySQL Database
		    $mysql_prefix   = $_POST['mysql_pr'];//MySQL Prefix.
            
            $request  = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	        $site_url = explode('/install/', $request);
	        $site_url = $site_url[0];
            
    	    if(!$mysql_host or !$mysql_username or !$mysql_password or !$mysql_database or !$mysql_prefix){//Check if all values are there.
    		    throw new Exception('All fields are required!');//If not, error.
		    }elseif(!$conn = @mysql_connect($mysql_host, $mysql_username, $mysql_password)){//Checks if MySQL connection could be established.
			    throw new Exception('MySQL Server connection could not be established.');//If not, error.
		    }elseif(!@mysql_select_db($mysql_database, $conn)){//Checks for connection to database.
			    throw new Exception('MySQL Database connection could not be established.');//If not, error.
    	    }else{
                
				/*
				 * Placing correct values into configuration file.
				 */
				$config = file_get_contents('config.php');
                $config = str_replace('%mysql_host%', $mysql_host, $config);
                $config = str_replace('%mysql_username%', $mysql_username, $config);
                $config = str_replace('%mysql_password%', $mysql_password, $config);
                $config = str_replace('%mysql_database%', $mysql_database, $config);
		        $config = str_replace('%mysql_prefix%', $mysql_prefix, $config);
                $config = str_replace('%site_url%', $site_url, $config);
                file_put_contents('../applications/config.php', $config);
                
			    /*
				 * Running SQL on Database
				 */
				$_SESSION['tangobb_install_step2'] = true;
                
                $MYSQL = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
                
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "forum_category` (`id` int(11) NOT NULL AUTO_INCREMENT,`category_title` varchar(255) NOT NULL,`category_desc` varchar(255) NOT NULL,`category_place` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
");
                $MYSQL->query("INSERT INTO `" . $mysql_prefix . "forum_category` (`id`, `category_title`, `category_desc`, `category_place`) VALUES (1, 'First Category', 'First category created.', 0);
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "forum_node` (`id` int(11) NOT NULL AUTO_INCREMENT,`node_name` varchar(255) NOT NULL,`name_friendly` varchar(255) NOT NULL,`node_desc` varchar(255) NOT NULL,`in_category` int(11) NOT NULL,`node_locked` int(11) NOT NULL DEFAULT '0',`node_place` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
");
                $MYSQL->query("INSERT INTO `" . $mysql_prefix . "forum_node` (`id`, `node_name`, `name_friendly`, `node_desc`, `in_category`, `node_locked`, `node_place`) VALUES (1, 'First Node', 'first_node', 'First node created.', 1, 0, 0);
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "forum_posts` (`id` int(11) NOT NULL AUTO_INCREMENT,`post_title` varchar(255) NOT NULL DEFAULT '',`title_friendly` varchar(255) NOT NULL,`post_content` text NOT NULL,`post_tags` varchar(255) NOT NULL,`post_time` int(11) NOT NULL,`post_user` int(11) NOT NULL,`origin_thread` int(11) NOT NULL DEFAULT '0',`origin_node` int(11) NOT NULL DEFAULT '0',`post_type` int(11) NOT NULL,`post_sticky` int(11) NOT NULL DEFAULT '0',`post_locked` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
                $MYSQL->query("ALTER TABLE  `{prefix}forum_posts` ADD  `last_updated` INT NOT NULL DEFAULT  '0' AFTER  `post_locked`");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "generic` (`id` int(11) NOT NULL AUTO_INCREMENT,`site_name` varchar(255) NOT NULL,`site_theme` varchar(255) NOT NULL,`site_language` varchar(255) NOT NULL,`site_email` varchar(255) NOT NULL,`register_email_activate` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "permissions` (`id` int(11) NOT NULL AUTO_INCREMENT,`permission_name` varchar(255) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;
");
                $MYSQL->query("INSERT INTO `" . $mysql_prefix . "permissions` (`id`, `permission_name`) VALUES (1, 'view_forum'),(2, 'create_thread'),(3, 'reply_thread'),(4, 'access_moderation'),(5, 'access_administration');
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "reports` (`id` int(11) NOT NULL AUTO_INCREMENT,`report_reason` varchar(255) NOT NULL,`reported_by` int(11) NOT NULL,`reported_user` int(11) NOT NULL DEFAULT '0',`reported_post` int(11) NOT NULL DEFAULT '0',`reported_time` int(11) NOT NULL,`report_close` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "usergroups` (`id` int(11) NOT NULL AUTO_INCREMENT,`group_name` varchar(255) NOT NULL,`group_style` varchar(255) NOT NULL DEFAULT '%username%',`group_permissions` varchar(255) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;
");
                $MYSQL->query("INSERT INTO `" . $mysql_prefix . "usergroups` (`id`, `group_name`, `group_style`, `group_permissions`) VALUES (1, 'User', '<span>%username%</span>', '1,2,3'),(2, 'Banned', '<span>%username% (Banned)</span>', '0'),(3, 'Moderator', '<span style=\"color:#3a5892;\"><strong>%username%</strong></span>', '1,2,3,4'),(4, 'Administrator', '<span style=\"color:#762727;\"><strong>%username%</strong></span>', '*');
");
                $MYSQL->query("CREATE TABLE IF NOT EXISTS `" . $mysql_prefix . "users` (`id` int(11) NOT NULL AUTO_INCREMENT,`username` varchar(255) NOT NULL,`user_password` varchar(255) NOT NULL,`user_email` varchar(255) NOT NULL,`user_message` varchar(255) NOT NULL DEFAULT 'User',`user_avatar` varchar(255) NOT NULL DEFAULT 'default.png',`user_signature` varchar(255) NOT NULL,`date_joined` int(11) NOT NULL,`user_birthday` date NOT NULL,`user_group` int(11) NOT NULL DEFAULT '1',`user_disabled` int(11) NOT NULL DEFAULT '0',`is_banned` int(11) NOT NULL DEFAULT '0',`unban_time` int(11) NOT NULL,`ban_reason` varchar(255) NOT NULL DEFAULT 'None',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
                $MYSQL->query("ALTER TABLE  `{prefix}users` CHANGE  `user_signature`  `user_signature` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;");
                
                echo '<div class="alert alert-success">Success! <a href="step3.php">Continue</a>.</div>';
				
    	    }
		
        }catch(Exception $e){
    	    echo '<div class="alert alert-danger">'.$e->getMessage().'</div>';
        }
		
    }

?>

<form action="" method="POST">
	<label for="host">MySQL Host</label>
	<input type="text" name="mysql_h" id="host" class="form-control" />
    
	<label for="username">MySQL Username</label>
	<input type="text" name="mysql_u" id="username" class="form-control" />
    
	<label for="password">MySQL Password</label>
	<input type="password" name="mysql_p" id="password" class="form-control" />
    
	<label for="database">MySQL Database</label>
	<input type="text" name="mysql_d" id="database" class="form-control" />
    
	<label for="prefix">MySQL Prefix</label>
	<input type="text" name="mysql_pr" id="prefix" class="form-control" value="tan_" />
    <br />
	<input type="submit" name="mysql" value="Test Connection & Continue" class="btn btn-default" />
</form>

<?php
  require_once('assets/bot.php');

?>