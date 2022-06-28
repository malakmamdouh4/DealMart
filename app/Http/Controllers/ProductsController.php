<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Favourite;
use Illuminate\Support\Facades\URL;


class ProductsController extends Controller
{
    
    // add new product 
    public function addProduct(Request $request)
    {

        $file = $request->file('image');

        if(!$file)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'file not found',
            ]);
        }
        else
        {
            $extension = $file->getClientOriginalExtension();
            $path = $file->store('public/products');
            $truepath = substr($path, 7);

            Product::create([
                'name' => $request->name ,
                'price' => $request->price ,
                'overview' => $request->overview ,
                'details' => $request->details ,
                'image' =>  URL::to('/') . '/storage/' . $truepath,
            ]);
    
            return response()->json([
                    'status' => 1 ,
                    'message' => 'product added successfully',
            ]);

        }
       
    }


      // show all products
      public function showProducts(Request $request)
      {
  
           $products = Product::select('id','name','price','image','overview','details')
           ->where('sold',0)->get();

           if(count($products) > 0 )
           {
                return response()->json([
                    'status' => 1 ,
                    'message' => 'All Products',
                    'products' => $products
                ]);
           }
           elseif(count($products) <= 0)
           {
               return response()->json([
                    'status' => 1 ,
                    'message' => 'No Products Yet',
                    'products' => null
                ]);
           }
           else
           {
                return response()->json([
                    'status' => 0 ,
                    'message' => 'Error',
                    'products' =>null
                ]);
           }
         
      }


        // show all products
        public function showProductDetails(Request $request)
        {
    
             $product = Product::where('id',$request->product_id)
             ->select('id','name','price','image','overview','details')
             ->first();

             $countOfProducts = Product::where('name',$product->name)->count();

             if($product)
             {
                  return response()->json([
                      'status' => 1 ,
                      'message' => 'Product Details',
                      'countOfProducts' => $countOfProducts ,
                      'product' => $product
                  ]);
             }
             elseif(!$product)
             {
                 return response()->json([
                      'status' => 1 ,
                      'message' => 'No Product',
                      'countOfProducts' => 0*1 ,
                      'product' => null
                  ]);
             }
             else 
             {
                  return response()->json([
                      'status' => 0 ,
                      'message' => 'Error',
                      'countOfProducts' =>0*1 ,
                      'product' =>null
                  ]);
             }
           
        }
  


    // show user favourites
    public function userFavourites(Request $request)
    {
        $user = User::find($request->user_id) ;

        $favourites = Product::select('id','name','price' ,'image' ,'overview')
            ->whereHas('favourites', function ($query) use ($user,$request) 
            { $query->where('user_id',$request->user_id) ; } )
            ->get();

        if(!$user || $request->user_id == null)
        {
            return response()->json([
                'status'  => 0 ,
                'message' => ' user not found ' ,
                'products' => null
            ]);
        }
        elseif ($user && count($favourites) > 0)
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user favourites' ,
                'products' => $favourites
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no products add to favourites yet' ,
                'products' => null
            ]);
        }

    }


    // show user cart
    public function userCart(Request $request)
    {
        $user = User::find($request->user_id) ;

        $carts = Product::select('id','name','price' ,'image' ,'overview')
            ->whereHas('carts', function ($query) use ($user,$request) 
            { $query->where('user_id',$request->user_id) ; } )
            ->get();

        if(!$user || $request->user_id == null)
        {
            return response()->json([
                'status'  => 0 ,
                'message' => ' user not found ' ,
                'products' => null
            ]);
        }
        elseif ($user && count($carts) > 0)
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user cart' ,
                'products' => $carts
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no products add to cart yet' ,
                'products' => null
            ]);
        }

    }


  

}
