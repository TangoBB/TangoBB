<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Tango\Database\Permission as Permission;
use Auth;

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

    public $timestamps = true;

    public function Group()
    {
        return $this->hasOne('App\Tango\Database\Group', 'id', 'group');
    }

    public static function Gravatar($user, $size = 1)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) .  "&s=" . $size;
    }

    public function hasPermission($user = null, $permission)
    {
        $perm       = Permission::where('permission_name', '=', $permission)->first();
        if( !empty($perm) ) {
            $group_permissions    = '';
            $excluded_permissions = '';
            if( Auth::check() )
            {
                if( $user == null )
                {
                    $auth                 = Auth::User();
                    $group_permissions    = $auth->Group()->first()['group_permissions'];
                    $excluded_permissions = $auth->excluded_permissions;
                }
            }
            else
            {
                if( $user == null )
                {
                    $group_permissions    = '1';//Give barebones permission to non-logged user.
                    $excluded_permissions = '';
                }
            }

            if( $user !== null )
            {
                $group_permissions    = $user->Group()->first()['group_permissions'];
                $excluded_permissions = $user->excluded_permissions;
            }

            //die(var_dump($group_permissions));
            if( $group_permissions == "*" )
            {
                return true;
            }
            else
            {
                $perms    = explode(',', $group_permissions);
                $ex_perms = explode(',', $excluded_permissions);
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
