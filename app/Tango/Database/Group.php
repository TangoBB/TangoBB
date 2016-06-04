<?php

namespace App\Tango\Database;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'group';

    protected $fillable = [
    	'group_name',
    	'group_style',
    	'group_permissions'
    ];

    public $timestamps = true;
}
