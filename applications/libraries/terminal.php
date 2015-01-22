<?php

/*
 * Terminal Library
 */

if (!defined('BASEPATH')) {
    die();
}

class Library_Terminal
{

    /*
     * Check is command exists.
     */
    public function commandExists($name)
    {
        global $MYSQL;
        //$MYSQL->where('command_name', $name);
        //query = $MYSQL->get('{prefix}terminal');
        $MYSQL->bind('command_name', $name);
        $query = $MYSQL->query("SELECT * FROM {prefix}terminal WHERE command_name = :command_name");
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Create new command.
     * $name - Must be lowercase.
     * $syntax - %s for arguements, example "cugroup %s %s". cugroup must be the same as in $name.
     * $function - Function to be ran when the command is called out. Full function terminal_FUNCTION(). Only FUNCTION() is allowed
     */
    public function create($name, $syntax, $function)
    {
        global $MYSQL;
        if ($this->commandExists($name)) {
            return false;
        } else {
            /*$data = array(
              'command_name' => $name,
              'command_syntax' => $syntax,
              'run_function' => $function
            );
            $MYSQL->insert('{prefix}terminal', $data)*/
            $MYSQL->bindMore(
                array(
                    'command_name' => $name,
                    'command_syntax' => $syntax,
                    'run_function' => $function
                )
            );
            if ($MYSQL->query("INSERT INTO {prefix}terminal (command_name, command_syntax, run_function) VALUES (:command_name, :command_syntax, :run_function)") > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function delete($name)
    {
        global $MYSQL;
        if ($this->commandExists($name)) {
            //$MYSQL->where('command_name', $name);
            //$MYSQL->delete('{prefix}terminal')
            $MYSQL->bind('command_name', $name);
            if ($MYSQL->query("DELETE FROM {prefix}terminal WHERE command_name = :command_name") > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}

?>