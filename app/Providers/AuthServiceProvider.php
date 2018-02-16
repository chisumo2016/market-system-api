<?php

namespace App\Providers;

use App\Buyer;
use App\Seller;
use App\User;
use App\Policies\BuyerPolicy;
use App\Policies\UserPolicy;
use App\Policies\SellerPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Buyer::class =>BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
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

        //

        //Scopes
        Passport::tokensCan([
            'purchase-product' =>'Create a new transaction for a specific product',
            'manage-products' =>'Create , Read , Update and Delete products (CRUD)',
            'manage-account' =>'Read  your account data , id , name , email, if verified and if admin (Cannot read password). 
              Modify your account data(email and password). Cannot delete your account',
            'read-general' => 'Read general information like purchasing categories , purchased products, selling products, selling categories
            , your transactions(purchases and sales)',
        ]);

    }
}
