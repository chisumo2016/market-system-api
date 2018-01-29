<?php

namespace App;
use App\Category;
use App\Seller;
use App\Transaction;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //Constant to check status
    const AVAILABLE_PRODUCT = 'available';
    const UNVAILABLE_PRODUCT = 'unavailable';

    protected  $fillable = ['name', 'description', 'quantity', 'status','image', 'seller_id'];


    //Product is available

    public function  isAvailable()
    {
        return $this->status == Product:: AVAILABLE_PRODUCT;
    }

    public function  transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function  seller()
    {
       return $this->belongsTo(Seller::class) ;
    }


    public  function  categories()
    {
        return $this->belongsToMany(Category::class);
    }



}
