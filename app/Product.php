<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //Constant to check status
    const AVAILABLE_PRODUCT = 'available';
    const UNVAILABLE_PRODUCT = 'unavalable';

    protected  $fillable = ['name', 'description', 'quantity', 'status','image', 'seller_id'];


    //Product is available

    public function  isAvalable()
    {
        return $this->status == Product:: AVAILABLE_PRODUCT;
    }
}
