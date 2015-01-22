<?php

if (!defined('BASEPATH')) {
    die();
}

class Crud_Generic extends Crud
{
    protected $table = '{prefix}generic';
    protected $pk = 'id';
}

?>