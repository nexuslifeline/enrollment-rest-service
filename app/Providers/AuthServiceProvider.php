<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        Passport::routes();
        //Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //Passport::tokensExpireIn(Carbon::now()->addMinutes(1)); // to test in 1 minute uncomment this one and comment the next line
        Passport::tokensExpireIn(Carbon::now()->addDays(30));
    }
}
