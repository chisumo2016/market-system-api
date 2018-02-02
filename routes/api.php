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
Route::resource('buyers.sellers' ,'Buyer\BuyerSellerController', ['only' => ['index' ]]);
Route::resource('buyers.products' ,'Buyer\BuyerProductController', ['only' => ['index' , 'show']]);
Route::resource('buyers.categories' ,'Buyer\BuyerCategoryController', ['only' => ['index' ]]);
Route::resource('buyers.transactions' ,'Buyer\BuyerTransactionController', ['only' => ['index' ]]);


/*
* API Routes  - Categories
*/

Route::resource('categories' ,'Category\CategoryController', ['except' => ['create' , 'edit']]);
Route::resource('categories.products' ,'Category\CategoryController', ['only' => ['index']]);

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
Route::resource('transactions.sellers' ,'Transaction\TransactionSellerController', ['only' => ['index']]);

/*
* API Routes  - Users
*/

Route::resource('users' ,'User\UserController', ['except' => ['create' , 'edit']]);


















//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});