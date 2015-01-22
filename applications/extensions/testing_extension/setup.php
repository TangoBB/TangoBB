<?php

class Extension_Setup extends Iko_Extensions_Setup
{

    public function __construct()
    {
        $this->extension_name = 'Testing Extension';//The name of your extension.
    }

    /*
     * Returns TRUE if installation goes well.
     * Returns FALSE if there is an error in the installation.
     */
    public function install()
    {
        //Anything here to install the extension.
        return true;
    }

    /*
     * Returns TRUE if uninstall goes well.
     * Returns FALSE if there is an error in the uninstall.
     */
    public function uninstall()
    {
        //Anything here to uninstall the extension.
        return true;
    }

}

?>