<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// register
Route::post('/register', [UserController::class, 'register']);

// user login
Route::post('/login', [UserController::class, 'login']);

// add new product 
Route::post('/add-product', [ProductsController::class, 'addProduct']);

// show all products 
Route::get('/show-products', [ProductsController::class, 'showProducts']);

// show all products 
Route::post('/show-product-details', [ProductsController::class, 'showProductDetails']);

// add product to favourites or deleted 
Route::post('/add-to-favourite', [UserController::class, 'addToFavourite']);

// user favourites
Route::post('/user-favourites', [ProductsController::class, 'userFavourites']);

// add product to cart
Route::post('/add-to-cart', [UserController::class, 'addToCart']);

// user carts
Route::post('/user-cart', [ProductsController::class, 'userCart']);