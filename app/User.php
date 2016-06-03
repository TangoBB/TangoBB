<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Tango\Database\Permission as Permission;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'excluded_permissions'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Group()
    {
        return $this->hasOne('App\Tango\Database\Group', 'id', 'group');
    }

    public static function Gravatar($user, $size = 1)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) .  "&s=" . $size;
    }

    public function hasPermission($user, $permission)
    {
        $perm       = Permission::where('permission_name', '=', $permission)->first();
        if( !empty($perm) )
        {
            $user_group = $user->Group()->first();
            if( $user_group == "*" )
            {
                return true;
            }
            else
            {
                $perms    = explode(',', $user_group['group_permissions']);
                $ex_perms = explode(',', $user->excluded_permissions);
                $ov_perms = [];

                foreach( $perms as $p )
                {
                    if( !in_array($p, $ex_perms) )
                    {
                        $ov_perms[] = $p;
                    }
                }
                //die(var_dump($ov_perms));
                return in_array($perm['id'], $ov_perms);
            }
        }

        return false;
    }
}
