<?php

namespace App\Tango\Database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $table = 'users';

    protected $fillable = [
    	'name',
    	'email',
    	'password',
    	'excluded_permissions'
    ];

    public function Group()
    {
        return $this->hasOne('App\Tango\Database\Group', 'id', 'group');
    }

    public function hasPermission($user, $permission)
    {
        die(var_dump($user->Group()));
        $perm = Permission::where('permission_name', '=', $permission)->first();
        if( !empty($perm) )
        {
            //$user_perms = (is_object($user))? explode(',', $user->) : '';
        }

        return false;
    }
}
