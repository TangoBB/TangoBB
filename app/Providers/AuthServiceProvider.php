<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
        //Checks if user can post in the category.
        $gate->define('post-in-category', function($user, $category) {
            $group = $user->Group()->first();
            if( $category['allow_posting'] == "*" )
            {
                return true;
            }
            else
            {
                $allow = explode(',', $category['allow_posting']);
                return in_array($group['id'], $allow);
            }
        });

        //Checks if user can update post.
        $gate->define('update-post', function($user, $post) {
            if( $user->id == $post['posted_by'] && $user->hasPermission(null, 'post.edit') )
            {
                return true;
            }
            else
            {
                return false;
            }
        });
    }
}
