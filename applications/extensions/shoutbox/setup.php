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
		global $MYSQL;
		$query ="CREATE TABLE 
		{prefix}shoutbox(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), timestamp VARCHAR(999), user VARCHAR(999), post VARCHAR(999))";
  		$MYSQL->query($query);
  		return true;
  	}

    /*
     * Returns TRUE if uninstall goes well.
     * Returns FALSE if there is an error in the uninstall.
     */
  	public function uninstall() {
		global $MYSQL;
		$query ="DROP TABLE IF EXISTS {prefix}shoutbox";
  		$MYSQL->query($query);
  		return true;
  	}

  }

?>