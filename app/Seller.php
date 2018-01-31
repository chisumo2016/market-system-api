<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Model;

class Seller extends  User
{
    //
    //Global Scope
    protected  static  function  boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }

    public  function  products()
    {
        return $this->hasMany(Product::class);
    }
}
