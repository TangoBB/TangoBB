<?php

  class Extension_Setup {

  	public $extension_name;

  	public function __construct() {
  		$this->extension_name = 'Shoutbox';//The name of your extension.
  	}

    /*
     * Returns TRUE if installation goes well.
     * Returns FALSE if there is an error in the installation.
     */
  	public function install() {
  		global $MYSQL, $TANGO;
		
		$shoutbox_posts = "
		CREATE TABLE {prefix}shoutbox_posts
		(
		id INT(11) NOT NULL AUTO_INCREMENT,
		type INT(11) NOT NULL DEFAULT 0,
		timestamp INT(30),
		user INT(11),
		message VARCHAR(999),
		cmd VARCHAR(999) NULL,
		deleted INT(1) DEFAULT 0,
		PRIMARY KEY (id)
		);
		";
		
	$shoutbox_bans = "
		CREATE TABLE {prefix}shoutbox_bans
		(
		id INT(11) NOT NULL AUTO_INCREMENT,
		user VARCHAR(999),
		PRIMARY KEY (id)
		);
		
		"; 

	$MYSQL->query($shoutbox_posts);
	$MYSQL->query($shoutbox_bans);
	$TANGO->perm->create('view_shoutbox');
  		return true;
  	}

    /*
     * Returns TRUE if uninstall goes well.
     * Returns FALSE if there is an error in the uninstall.
     */
  	public function uninstall() {
		global $MYSQL;
  		$MYSQL->query("DROP TABLE IF EXISTS {prefix}shoutbox_posts");
  		$MYSQL->query("DROP TABLE IF EXISTS {prefix}shoutbox_bans");
		$MYSQL->where("permission_name","view_shoutbox");
		$MYSQL->delete("{prefix}permissions");
  		return true;
  	}

  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  /* Sean Davies - http://seandavies.pw */
  

?>