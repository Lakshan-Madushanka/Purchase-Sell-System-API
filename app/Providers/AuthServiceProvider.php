<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

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
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();
        Passport::tokensCan([
            'purchase-products' => 'Create a new transaction for a specific product',
            'manage-products' => 'Create, read, update and delete products (CRUD)',
            'manage-account' => 'Read your account data, id, name, email, if verified, and if admin
             (cannot read password). Modify your account data (email and password) cannot delete 
             your account',
            'read-gereral' => 'Read gerenel information like purchasing categories, purchesed products,
            selling products, selling categories, your transactions (purchases and sales)',
        ]);
    }
}
