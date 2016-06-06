<?php

namespace App\Tango\Database;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'permission';

    protected $fillable = [
    	'permission_name',
    	'created_at',
    	'updated_at'
    ];

    public $timestamps = true;
}
