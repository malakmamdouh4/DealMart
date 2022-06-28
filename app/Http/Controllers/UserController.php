<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Favourite;
use App\Models\Cart;


class UserController extends Controller
{
    

     // user register
     public function register(Request $request)
     {

         $validator = Validator::make($request->all(), [
             'email' => 'required|string|email|max:100|unique:users',
             'phone' => 'required|string|max:100|unique:users',
             'name' => 'required',
             'password' => 'required'
         ]);
 
         if($validator->fails()){
             return response()->json([
                 'status' => 0 ,
                 'message' => 'failed, inputs are required',
             ]);
         }
 
         User::create([
             'name' => $request->name ,
             'password' => bcrypt($request->password),
             'phone' => $request->phone ,
             'email' => $request->email ,
         ]);
  
         return response()->json([
                 'status' => 1 ,
                 'message' => 'suucess , user registered successfully',
         ]);
 
     }


     // user login
     public function login(Request $request)
     {

        if ( $token = auth()->attempt(['phone' => request('username'), 'password' => request('password')]) ||
             $token = auth()->attempt(['email' => request('username'), 'password' => request('password')]) )
        {
            return $this->createNewToken($token);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'Unauthorized',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
            ]);
        }
        
     }


      // user token
    protected function createNewToken($token)
    {
       $user = auth()->user() ;
        return response()->json([
            'status' => 1 ,
            'message' => 'user loggined successfully',
            'userId' => $user->id*1 ,
            'userName' => $user->name ,
            'userEmail' =>$user->email ,
            'userPhone' => $user->phone ,
        ]);
    }



    //  add products to favourites or deleted
    public function addToFavourite(Request $request)
    {
        $user = User::find($request->user_id) ;
        $product = Product::find($request->product_id) ;
        $favourites = Favourite::where([['user_id',$request->user_id],['product_id',$request->product_id]])->first();

        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif(!$product || $request->product_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'product not found',
            ]);
        }
        elseif ($favourites)
        {
            $favourites->delete();

            return response()->json([
                'status' => 1 ,
                'message' => 'product removed form favourites',
            ]);
        }
        elseif(!$favourites && $user && $product)
        {
              Favourite::firstOrCreate([
                'user_id'  => $request->user_id,
                'product_id'  => $request->product_id,
              ]);

            return response()->json([
                'status' => 1 ,
                'message' => 'product add to favourites',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error',
            ]);
        }

    }


     //  add products to favourites or deleted
     public function addToCart(Request $request)
     {
         $user = User::find($request->user_id) ;
         $product = Product::find($request->product_id) ;
         $cart = Cart::where([['user_id',$request->user_id],['product_id',$request->product_id]])->first();
 
         if(!$user || $request->user_id == null )
         {
             return response()->json([
                 'status' => 0 ,
                 'message' => 'user not found',
             ]);
         }
         elseif(!$product || $request->product_id == null )
         {
             return response()->json([
                 'status' => 0 ,
                 'message' => 'product not found',
             ]);
         }
         elseif($cart)
         {
            return response()->json([
                'status' => 0 ,
                'message' => 'product already in cart',
            ]);
         }
         elseif( $user && $product)
         {
               Cart::firstOrCreate([
                 'user_id'  => $request->user_id,
                 'product_id'  => $request->product_id,
               ]);
 
             return response()->json([
                 'status' => 1 ,
                 'message' => 'product add to cart',
             ]);
         }
         else
         {
             return response()->json([
                 'status' => 0 ,
                 'message' => 'error',
             ]);
         }
 
     }


}
