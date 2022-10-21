<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];
     
    public function __construct()
    {
        $this->user=new User();
    }
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSuperAdmin', function($user){
            return $this->user->getType($user->id)=='3';
        });
        Gate::define('isAdmin', function($user){
            return $this->user->getType($user->id)=='1';
        });
        Gate::define('isUser', function($user){
            return $this->user->getType($user->id)=='2';
        });
    }
    
}
