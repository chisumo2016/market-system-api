<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
* API Routes  - Buyers
*/

Route::resource('buyers' ,'Buyer\BuyerController', ['only' => ['index' , 'show']]);


/*
* API Routes  - Categories
*/

Route::resource('categories' ,'Category\CategoryController', ['except' => ['create' , 'edit']]);

/*
* API Routes  - Products
*/

Route::resource('products' ,'Product\ProductController', ['only' => ['index' , 'show']]);

/*
* API Routes  - Sellers
*/

Route::resource('sellers' ,'Seller\SellerController', ['only' => ['index' , 'show']]);

/*
* API Routes  - Transactions
*/

Route::resource('transactions' ,'Transaction\TransactionController', ['only' => ['index' , 'show']]);
Route::resource('transactions.categories' ,'Transaction\TransactionCategoryController', ['only' => ['index']]);

/*
* API Routes  - Users
*/

Route::resource('users' ,'User\UserController', ['except' => ['create' , 'edit']]);


















//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});