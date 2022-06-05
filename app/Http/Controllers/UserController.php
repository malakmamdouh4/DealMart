<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


     

}
