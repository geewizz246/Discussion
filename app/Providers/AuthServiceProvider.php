<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Discussion;
use App\Post;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define gate for profile owner. 
        Gate::define('isMyProfile', function($user, $otherUser) {
            return $user->id === $otherUser->id;
        });

        // Define gate for discussion owner.
        Gate::define('isMyDiscussion', function($user, $discussion) {
            return $user->id === $discussion->user_id;
        });

        // Define gate for post owner.
        Gate::define('isMyPost', function($user, $post) {
            return $user->id === $post->user->id;
        });
    }
}
