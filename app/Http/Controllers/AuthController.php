<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $inputs = $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|unique:users,email',
           'password'=>'required|string|confirmed',
        ]);

        $user = User::create([
            'name'=> $inputs['name'],
            'email'=> $inputs['email'],
            'password'=> bcrypt($inputs['password']),
        ]);

        $token = $user->createToken('builtinvoicetoken')->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return response()->json([
            'success' => true,
            'message' => 'User Created Successfully',
            'data' => $response
        ], 200);
    }
}
