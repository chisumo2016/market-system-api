<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;//use App\Http\Controllers\Controller;

class BuyerController extends ApiController    // class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers);

        //return response()->json(['data' => $buyer], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
        //$buyer = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($buyer);
        //return response()->json(['data' => $buyer], 200);
    }


}
