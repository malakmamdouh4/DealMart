<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;


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


     public function login(Request $request)
     {

        $credentials  =  $request->only('email', 'password');
        $user = User::where('email',$request->email)->first();

        if (! $token = auth()->attempt($credentials))
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
            return $this->createNewToken($token);

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

}
