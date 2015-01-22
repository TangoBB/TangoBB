<?php

if (!defined('BASEPATH')) {
    die();
}

/*
 * Attempting to create a command named "echo"
 */
if (!$TERMINAL->commandExists('echo')) {
    $TERMINAL->create(
        'echo',//Name of the command. Will be converted into lowercase.
        'echo %s %s',//The command MUST BE the same as the name. %s will represent arguements.
        'run_echo'//The function that will be ran when the command is called. Will be ran as "terminal_run_echo"
    );

    function terminal_run_echo($say, $do)
    {
        global $ADMIN;
        if ($do) {
            return $ADMIN->alert(
                'Echoing out:' . $say,
                'success'
            );
        } else {
            throw new Exception ('Arguement is false, command not ran.');
        }
    }

}

?>