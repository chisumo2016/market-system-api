<?php

namespace App\Providers;


use App\Mail\UserCreated;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //


        Product::updated(function($product){
            if ($product->quantity == 0 && $product->isAvailable){
                $product->status = Product::UNVAILABLE_PRODUCT;

                $product->save();
            }
        });


        User::created(function ($user) {
            Mail::to($user)->send(new UserCreated($user));
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
