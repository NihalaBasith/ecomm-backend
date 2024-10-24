<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    function register(Request $request){


    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

   
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
    ]);


    return response()->json([
        'user' => $user,
        'message' => 'User registered successfully',
    ], 201); 
    }


    function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' =>'required|string|min:8'
        ]);

        $user = User::where('email',$request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json([
                'user' => $user,
                'message' => 'User Logged successfully',
            ], 201); 
        }
       
    }
}
