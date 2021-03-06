<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;

use App\Transformers\ProductTransformer;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class sellerProductController extends ApiController
{
    public function  __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . ProductTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:view, seller')->only('index');
        $this->middleware('can:sale, seller')->only('store');
        $this->middleware('can:edit-product, seller')->only('update');
        $this->middleware('can:delete-product, seller')->only('destroy');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        if(request()->user()->tokenCan('read-general') ||  request()->user()->tokenCan('manage-products')){
            $products = $seller->products;
            return $this->showAll($products);
        }

        throw new AuthorizationException("Invalid scope(s)");

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        //
        $rules = [
            'name'         => 'required',
            'description'  => 'required',
            'quantity'     => 'required|integer|min:1',
            'image'         => 'required|image',
        ];
        //validate
        $this->validate($request, $rules);

        //Show evertthing is ok
        $data = $request->all();

        $data['status'] = Product::UNVAILABLE_PRODUCT;
        $data['image']  =  $request->image->store('');      // $data['image']  =  '1.jpg';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        //

        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNVAILABLE_PRODUCT,
            'image'  =>'image',
        ];
        //Validate
        $this->validate($request, $rules);
        $this->checkSeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')){
            $product->status = $request->status;

            if($product->isAvailable() && $product->categories()->count() === 0){
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        //Image Update the image

        if($request->hasFile('image')){
            Storage::delete($product->image);
            $product->image = $request->image->$this->store('');
        }

        if ($product->isClean()){
            return $this->errorResponse('You need to specify a different value to update ', 422);
        }
        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        //
        $this->checkSeller($seller, $product);


        $product->delete();

        Storage::delete($product->image);

        return $this->showOne($product);
    }


    //Method to check
    protected  function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
            throw new  HttpException(422, 'The specified seller is not the actual seller of the product');
        }
    }

}
