<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        //
        $buyers = $product->transactions()   //        $buyers = $product->buyers ;
            ->with('buyer')
            ->get()
            ->pluck('buyer')
            ->unique('id')
            ->values('id');

        return $this->showAll($buyers );
    }


}
