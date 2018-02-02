<?php

namespace App;
use App\Category;
use App\Seller;
use App\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    protected  $dates = ['deleted_at'];


    //Constant to check status
    const AVAILABLE_PRODUCT = 'available';
    const UNVAILABLE_PRODUCT = 'unavailable';

    protected  $fillable = ['name', 'description', 'quantity', 'status','image', 'seller_id'];
    protected  $hidden = ['pivot'];

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
